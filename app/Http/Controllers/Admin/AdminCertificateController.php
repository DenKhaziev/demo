<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Child;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;


class AdminCertificateController extends Controller
{
    public function index()
    {
        $certRequests = Certificate::with(['child.user'])->where('is_has_a_certificate', false)->get();
        return Inertia::render('Admin/AdminCertificates', [
            'certificates' => $certRequests
        ]);
    }

    public function update(Certificate $certificate)
    {
        $certificate->update(['is_has_a_certificate' => true]);
        $certificate->child()->update([
            'is_has_a_certificate' => true
        ]);
        return Redirect::back()->with('success', 'справка выдана');
    }

    public function generate(Request $request, Child $child)
    {
        $passedTests = $child->passedTests()
        ->where('test_type_id', '!=', 1)
        ->where('grade_id', $child->grade_id)
        ->with('subject')
        ->get();
        // 1. Генерируем PDF
        $pdf = Pdf::loadView('pdf.certificate', [
            'child' => $child,
            'passedTests' => $passedTests,
        ]);

        // 2. Проверяем, есть ли уже справка — если есть, удаляем старый файл
        $existing = Certificate::where('child_id', $child->id)
            ->where('grade_id', $child->grade_id)
            ->first();

        if ($existing && $existing->filename) {
            Storage::disk('public')->delete("certificates/{$existing->filename}");
        }

        // 3. Генерируем уникальное имя файла
        $filename = 'certificate_' . $child->id . '_' . $child->grade_id . '_' . Str::random(6) . '.pdf';

        // 4. Сохраняем новый файл
        Storage::disk('public')->put("certificates/{$filename}", $pdf->output());

        // 5. Обновляем или создаём запись в таблице certificates
        Certificate::updateOrCreate(
            [
                'child_id' => $child->id,
                'grade_id' => $child->grade_id,
            ],
            [
                'filename' => $filename
            ]
        );

//        return back()->with('success', 'Справка успешно сформирована и сохранена.');
        // 6. Отправляем файл на скачивание
        return response()->json(['success' => true]);
    }

    public function download(Child $child)
    {
        $certificate = Certificate::where('child_id', $child->id)
            ->where('grade_id', $child->grade_id)
            ->firstOrFail();

        $path = storage_path("app/public/certificates/{$certificate->filename}");

        return response()->download($path, $certificate->filename);
    }

    public function count()
    {
        $count = Certificate::where('is_has_a_certificate', false)->count();
        return response()->json(['count' => $count]);
    }

    // request to store table Certificates

}
