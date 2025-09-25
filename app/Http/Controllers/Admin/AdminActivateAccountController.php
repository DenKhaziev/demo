<?php

namespace App\Http\Controllers\Admin;

use App\Events\ActivateStudentAccount;
use App\Events\TicketMessageAdded;
use App\Http\Controllers\Controller;
use App\Http\Resources\TicketBroadcastResource;
use App\Models\Child;
use App\Models\DocumentByGrade;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class AdminActivateAccountController extends Controller
{
    public function activateAccess()
    {
        $requestsToAccess = DocumentByGrade::with(['child.user'])
            ->whereHas('child', function ($query) {
                $query->whereColumn('documents_by_grades.grade_id', 'children.grade_id')
                    ->where('is_payment', 0);
            })
            ->whereNotNull('document_birth')
            ->whereNotNull('document_application_for_transfer')
            ->whereNotNull('document_personal_of_processing_data')
            ->whereNotNull('document_payment')
            ->where(function ($query) {
                $query->where('grade_id', '<=', 9)
                    ->orWhere(function ($q) {
                        $q->where('grade_id', '>', 9)
                            ->whereNotNull('document_from_9_graduate');
                    });
            })
            ->get();

        return Inertia::render('Admin/AdminAccessToAccountRequests', [
            'accessToAccount' => $requestsToAccess
        ]);
    }
    public function count()
    {
        $requestsToAccess = DocumentByGrade::with(['child.user'])
            ->whereHas('child', function ($query) {
                $query->whereColumn('documents_by_grades.grade_id', 'children.grade_id')
                    ->where('is_payment', 0);
            })
            ->whereNotNull('document_birth')
            ->whereNotNull('document_application_for_transfer')
            ->whereNotNull('document_personal_of_processing_data')
            ->whereNotNull('document_payment')
            ->where(function ($query) {
                $query->where('grade_id', '<=', 8)
                    ->orWhere(function ($q) {
                        $q->where('grade_id', '>', 8)
                            ->whereNotNull('document_from_9_graduate');
                    });
            })
            ->count();
        return response()->json(['count' => $requestsToAccess]);
    }

    public function update(DocumentByGrade $documentByGrade, NotificationService $notificationService)
    {
        $child = $documentByGrade->child;
        $child->update(['is_payment' => true, 'payment_date' => now()]);

        $parent = $child->user;
        if ($parent && $parent->email) {
            $notificationService->sendAccessActivated($parent);
        }
        return Redirect::back()->with('success', 'Активация аккаунта выполнена');
    }

    public function destroy(DocumentByGrade $documentByGrade)
    {
        $activateChildRequest = $documentByGrade;
        $activateChildRequest->delete();

        return Redirect::back()->with('success', 'Запрос переведен в архив');
    }

    public function unactive(DocumentByGrade $documentByGrade)
    {
        $activateChildRequest = $documentByGrade;
        $activateChildRequest->update(['updated_at' => null]);

        return Redirect::back()->with('success', 'Запрос переведен в неактивный');
    }
}
