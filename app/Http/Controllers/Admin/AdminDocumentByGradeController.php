<?php

namespace App\Http\Controllers\Admin;

use App\Events\ActivateStudentAccount;
use App\Http\Controllers\Controller;
use App\Models\Child;
use App\Models\DocumentByGrade;
use App\Models\Grade;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Services\ImageCompressor;


class AdminDocumentByGradeController extends Controller
{

public function upload(Request $request, Child $child, Grade $grade, string $field, NotificationService $notificationService)
{
    $request->validate([
        'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
    ]);

    // Найти или создать запись
    $document = DocumentByGrade::firstOrNew([
        'child_id' => $child->id,
        'grade_id' => $grade->id,
    ]);

    // Удалить старый файл, если был
    if ($document->$field) {
        Storage::disk('public')->delete("documents/{$child->id}/{$grade->id}/" . $document->$field);
    }

    // Подготовка путей
    $uploadedFile = $request->file('file');
    $extension = strtolower($uploadedFile->getClientOriginalExtension());
    $path = "documents/{$child->id}/{$grade->id}";

    // Если изображение — сжать и сохранить
    if (in_array($extension, ['jpg', 'jpeg', 'png'])) {
        $filename = app(ImageCompressor::class)->compressAndStore($uploadedFile, $path);
    } else {
        // Иначе просто сохранить (например, pdf)
        $filename = Str::random(12) . '.' . $extension;
        $uploadedFile->storeAs($path, $filename, 'public');
    }

    // Обновить поле и сохранить
    $document->$field = $filename;
    $document->save();

    // Email уведомление, если загружена справка о прохождении аттестации
    if ($field === 'document_certificate_by_grade') {
        $notificationService->sendCertificateUploaded($document->child->user, $document);
    }

    // Email уведомление, если загружена справка о зачислении
    if ($field === 'document_about_transfer') {
        $notificationService->sendDocumentAboutTransferUploaded($document->child->user, $document);
    }

    // Повторно загружаем связи
    $document->load('child.user');

    // Проверка всех документов
    $hasAllDocs =
        $document->document_birth &&
        $document->document_application_for_transfer &&
        $document->document_personal_of_processing_data &&
        $document->document_payment &&
        ($document->grade_id <= 8 || ($document->grade_id > 8 && $document->document_from_9_graduate));

    // Активация, если всё ок
    if ($hasAllDocs && !$document->child->is_payment) {
        broadcast(new ActivateStudentAccount(collect([$document])));
    }

    return back()->with('success', 'Документ загружен.');
}


}
