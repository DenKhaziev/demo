<?php

namespace App\Http\Controllers\Document;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use ZipArchive;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\Child;



class DocumentController extends Controller
{
    public function downloadDocument(Request $request)
    {
        $file = $request->get('file');

        // Безопасность — только конкретные имена, чтобы нельзя было лезть выше директории
        $allowed = [
            'soglasie_OPD.pdf',
            'Rekvizity_IP_2025.pdf',
            'obrazetc_pp_PA_2025.pdf',
            'obrazetc_pp_GIA_2025.pdf',
            'polozh_ob_organiz_prohozhd_attestatcii_semeyn_i_samoobr_02.09.2024.pdf',
            'oferta_PA.pdf',
            'oferta_GIA.pdf',
            'dogovor_o_sotrud.pdf',
            'study_map_elementary.pdf',
            'study_map_main.pdf',
            'study_map_basic.pdf'
        ];

        if (!in_array($file, $allowed)) {
            abort(403, 'Недопустимое имя файла');
        }

        $path = 'forDownloadsDocs/' . $file;

        if (!Storage::disk('public')->exists($path)) {
            abort(404, 'Файл не найден в хранилище');
        }

        return Storage::disk('public')->download($path, $file);
    }

    public function partnership()
    {
        return Inertia::render('Policy/Partnership');
    }

}
