<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;

class ImageCompressor
{
    protected ImageManager $manager;

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }

    public function compressAndStore(
        UploadedFile $file,
        string $path,
        ?string $filename = null,
        int $maxSizeKb = 5120
    ): string {

        $filename ??= Str::random(12) . '.jpg';
        $fileSizeKb = $file->getSize() / 1024;

        // Файл до 200 КБ — не трогаем, просто копируем
        if ($fileSizeKb <= $maxSizeKb) {
            Storage::disk('public')->putFileAs($path, $file, $filename);
            return $filename;
        }
        $image = $this->manager->read($file->getRealPath());

        // Базовое качество в зависимости от размера
        if ($fileSizeKb <= 500) {
            $quality = 85; // легкое сжатие
        } elseif ($fileSizeKb <= 2000) {
            $quality = 75; // среднее сжатие
        } else {
            $quality = 60; // сильное сжатие
        }

        $encoded = $image->toJpeg($quality);

        // Снижаем качество до нужного размера
        while (strlen($encoded) > $maxSizeKb * 1024 && $quality > 10) {
            $quality -= 5;
            $encoded = $image->toJpeg($quality);
        }

        Storage::disk('public')->put($path . '/' . $filename, $encoded);

        return $filename;
    }
}
