<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ChildrenReportExport;
use Illuminate\Support\Facades\Storage;

class SendChildrenReport extends Command
{
    protected $signature = 'report:send';
    protected $description = 'Генерация и отправка Excel-отчета по ученикам';

public function handle()
{
    $filePath = 'reports/children_report.xlsx';

    // 1) на всякий случай создаём папку
    Storage::disk('local')->makeDirectory('reports');

    // 2) генерим Excel
    $ok = Excel::store(new ChildrenReportExport, $filePath, 'local');
    if (!$ok) {
        $this->error('Excel::store вернул false');
        return 1;
    }

    // 3) проверяем, что файл есть
    if (!Storage::disk('local')->exists($filePath)) {
        $this->error('Файл не найден: '.Storage::disk('local')->path($filePath));
        return 1;
    }
    $absolute = Storage::disk('local')->path($filePath);

    // 4) отправляем ПЛЕЙН-ТЕКСТ письмо с вложением (без view, без setBody)
    Mail::raw('Отчёт по ученикам во вложении.', function ($m) use ($absolute) {
        $m->to('so@penaty.ru')
          ->subject('Отчёт по ученикам — '.now()->format('d.m.Y'))
          ->attach($absolute);
    });

    $this->info('Отчёт отправлен! Файл: '.$absolute);
}
}
