<?php

namespace App\Http\Controllers;

use App\Events\CertificateRequest;
use App\Events\PersonalAffairRequest;
use App\Events\TicketCreated;
use App\Events\TicketMessageAdded;
use App\Http\Requests\PersonalInfo\StorePersonalInfoRequest;
use App\Http\Requests\TicketMessage\StoreTicketMessageRequest;
use App\Http\Requests\TicketMessage\StoreTicketRequest;
use App\Http\Resources\TicketBroadcastResource;
use App\Models\Certificate;
use App\Models\Child;
use App\Models\DocumentByGrade;
use App\Models\Message;
use App\Models\Ticket;
use App\Models\UserInfo;
use App\Services\NotificationService;
use App\Services\TestService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Notifications\NotificationSender;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;


class ParentController extends Controller
{
    public function index()
    {
        $parent = auth()->user()->load('tickets');
        $children = $parent->children()->with('documents', 'tickets')->get();
        return Inertia::render('User/ParentMain', [
            'parent' => $parent,
            'children' => $children,
        ]);
    }

    public function create()
    {
        return Inertia::render('CertificateRequestSend');
    }

    public function storeCertificate(Request $request)
    {
        $data = $request->validate([
            'grade_id' => 'required|numeric|max:11',
            'child_id' => 'required|numeric',
        ]);
        $certificate = Certificate::firstOrCreate(
            [
                'child_id' => $data['child_id'],
                'grade_id' => $data['grade_id'],
            ],
            [
                'is_has_a_certificate' => false,
            ]
        );
        $certificate->load('child.user');

        broadcast(new CertificateRequest($certificate));

        return Redirect::back()->with('success', 'Запрос на формирование справки направлен');
    }

    public function personalInfosStore(StorePersonalInfoRequest $request)
    {

        $data = $request->validated();
        $data['user_id'] = auth()->id();

        UserInfo::updateOrCreate(
            ['child_id' => $data['child_id'], 'user_id' => auth()->id()],
            $data
        );
    }
    public function preview($childId)
    {
        $child = Child::with(['grade', 'userInfo'])->findOrFail($childId);
        $userInfo = $child->userInfo;

        if (!$child) {
            dd('Child not found');
        }
        if (!$child->userInfo) {
            dd('UserInfo not found');
        }

        $pdfData = [
            'parent_full_name' => $userInfo->parent_name,
            'parent_birth_date' => \Carbon\Carbon::parse($userInfo->parent_birth_date)->format('d.m.Y'),
            'parent_passport_series' => $userInfo->parent_passport_series,
            'parent_passport_number' => $userInfo->parent_passport_number,
            'parent_passport_issued_at' => \Carbon\Carbon::parse($userInfo->parent_passport_issued_at)->format('d.m.Y'),
            'parent_passport_issued_by' => $userInfo->parent_passport_issued_by,
            'parent_passport_department_code' => $userInfo->parent_passport_department_code,
            'parent_address' => $userInfo->parent_address,
            'parent_email' => auth()->user()->email ?? '—',
            'parent_phone' => auth()->user()->phone ?? '—',
            'child_full_name' => $child->name,
            'grade' => $child->grade_id ?? '-',
        ];

        $pdf = Pdf::loadView('pdf.application', $pdfData);
        return $pdf->stream('zayavlenie.pdf');
    }

    public function childShow(Child $child)
    {
        [$remainingTests, $passedTests] = TestService::showAllTests($child);
        return Inertia::render('User/ParentChildShow', [
            'remainingTests' => $remainingTests,
            'passedTests' => $passedTests,
            'child' => $child->load('user', 'certificate', 'documents')
        ]);
    }

    public function addChild()
    {
        return Inertia::render('Auth/AddNewStudentByUser');
    }

    public function storeChild(Request $request, NotificationService $notificationService)
    {

        $parent = auth()->user();

        $request->validate([
            'child_name' => 'required|string|max:255',
            'grade_id' => 'required|integer|min:1|max:11',
            'attestation_type' => 'required|string|in:ПА,ГИА',
        ]);
        // 1) Нормализуем ФИО: режем пробелы по краям и схлопываем множественные
        $normalizedName = trim(preg_replace('/\s+/u', ' ', $request->input('child_name')));
        $request->merge(['child_name' => $normalizedName]);

        // 2) Валидация + уникальность name в рамках user_id
        $request->validate([
            'child_name' => [
                'required','string','max:255',
                Rule::unique((new \App\Models\Child)->getTable(), 'name')
                    ->where(fn($q) => $q->where('user_id', $parent->id))
            ],
            'grade_id' => 'required|integer|min:1|max:11',
            'attestation_type' => 'required|string|in:ПА,ГИА',
        ], [
            'child_name.unique' => 'Ученик с таким ФИО уже есть в вашем списке.',
        ]);

        // Генерация логина и пароля ученика
        $prefix = 'student' . $parent->id . '_';

        // Получим всех детей с таким префиксом
        $lastEmail = Child::where('email', 'like', $prefix . '%@penaty.ru')
            ->orderByDesc('id')
            ->pluck('email')
            ->first();

        $nextNumber = 1;

        if ($lastEmail) {
            // Извлекаем число из email
            if (preg_match('/^student' . $parent->id . '_(\d+)@penaty\.ru$/', $lastEmail, $matches)) {
                $nextNumber = (int) $matches[1] + 1;
            }
        }
            $studentPassword = Str::random(8);
            $childEmail = $prefix . $nextNumber . '@penaty.ru';
            $student = Child::create([
                'user_id' => $parent->id,
                'name' => $request->child_name,
                'grade_id' => $request->grade_id,
                'attestation_type' => $request->attestation_type,
                'email' => $childEmail,
                'password' => Hash::make($studentPassword),
            ]);

            // $parent->notify(new \App\Notifications\AddNewStudentRegisteredNotification($student->email, $studentPassword));
            $notificationService->sendNewStudentAccess($parent, $student->email, $studentPassword);

            session()->flash('student_credentials', [
                'login' => $student->email,
                'password' => $studentPassword,
            ]);

        return redirect()->route('parent.index')->with('success', 'Ученик успешно добавлен');
    }

    public function personalAffairUpdate(DocumentByGrade $documentByGrade)
    {
        $documentByGrade->update(['has_personal_affair' => 0]);
        $documentByGrade->refresh()->load('child.user');
        broadcast(new PersonalAffairRequest($documentByGrade));

        return Redirect::back()->with('success', 'Запрос на формирование личного дела отправлен');
    }

    public function ticketMessages()
    {
        $user = auth()->user();
        $ticket = $user->tickets()->where('status', 'open')->latest()->first();

        if (!$ticket) {
            return redirect()->route('parent.ticket.create')
                ->with('error', 'Ваше обращение уже закрыто. Вы можете создать новое.');
        }

        return Inertia::render('User/ParentTicket', [
            'ticket' => $ticket->load('messages'),
            'messages' => $ticket->messages,
        ]);
    }

    public function storeTicket(StoreTicketRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        // Создаём тикет
        $ticket = Ticket::create([
            'user_id' => $data['user_id'],
            'subject' => $data['subject'],
            'status' => 'open',
            'viewed_by_admin' => false,
            'viewed_by_user' => true,
        ]);

        // Создаём первое сообщение
        $message = Message::create([
            'ticket_id' => $ticket->id,
            'user_id' => $data['user_id'],
            'body' => $data['message'],
        ]);
        $ticket->load(['user', 'latestMessage']);
        broadcast(new TicketCreated(new TicketBroadcastResource($ticket)));
        return redirect()->route('parent.ticket');
    }

    public function storeMessages(StoreTicketMessageRequest $request)
    {
        $user = auth()->user();

        $ticket = $user->tickets()->latest()->first();

        if ($ticket->status === 'closed') {
            $ticket->update(['status' => 'open']);
        }

        $data = $request->validated();

        // Создание нового сообщения
        $message = $ticket->messages()->create([
            'user_id' => $user->id,
            'body' => $data['body'],
            'is_admin' => $user->is_admin ?? false,
        ]);

        $ticket->update([
            'viewed_by_admin' => false,
            'viewed_by_user' => true,
        ]);

        // Подгружаем связи для broadcast
        $ticket->load(['user', 'latestMessage']);

        broadcast(new TicketMessageAdded(new TicketBroadcastResource($message)));

        return redirect()->back();
    }


    public function markAsRead(Ticket $ticket)
    {
        $user = auth()->user();

        // Проверяем, что этот тикет принадлежит текущему пользователю
        if ($ticket->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        // Ставим true, только если был false
        if (!$ticket->viewed_by_user) {
            $ticket->update(['viewed_by_user' => true]);
        }

        return response()->json(['status' => 'marked as read']);
    }

    public function ticketCreate()
    {
        return Inertia::render('User/CreateTicket');

    }
    public function destroy(Message $message)
    {
        if ($message->user_id === Auth::id()) {
            $message->delete();
        }
        return redirect()->back();
    }

    public function payment()
    {
        return Inertia::render('User/Payment', [
            'prices' => config('prices')
        ]);
    }

    public function unreadTicket ()
    {
        $user = auth()->user();

        $children = $user->children()->with(['tickets' => function ($q) {
            $q->where('status', 'open')->where('viewed_by_user', false);
        }])->get();

        $hasUnread = $children->pluck('tickets')->flatten()->isNotEmpty();

        return response()->json([
            'hasUnread' => $hasUnread,
        ]);
    }


}
