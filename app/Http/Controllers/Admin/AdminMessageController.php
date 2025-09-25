<?php

namespace App\Http\Controllers\Admin;

use App\Events\TicketMessageAdded;
use App\Http\Controllers\Controller;
use App\Http\Requests\TicketMessage\StoreTicketMessageRequest;
use App\Http\Resources\TicketBroadcastResource;
use App\Models\Message;
use App\Models\Ticket;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AdminMessageController extends Controller
{
    public function index(Ticket $ticket)
    {
        $messages = $ticket->messages()->orderBy('created_at')->get();
        if (auth()->id() === $ticket->user_id && !$ticket->is_viewed) {
            $ticket->update(['viewed_by_user' => true]);
        }

        if (auth()->user()->is_admin) {
            $ticket->update(['viewed_by_admin' => true]);
        }

        return Inertia::render('Admin/AdminMessages', [
            'ticket' => $ticket->load('user'),
            'messages' => $messages,
        ]);


    }

    public function store(StoreTicketMessageRequest $request, Ticket $ticket, NotificationService $notificationService)
    {
        $data = $request->validated();

        // Создание нового сообщения
        $message = $ticket->messages()->create([
            'user_id' => auth()->id(),
            'body' => $data['body'],
            'is_admin' => auth()->user()->is_admin ?? false,
        ]);
        $ticket->update([
            'viewed_by_admin' => true,
            'viewed_by_user' => false,
        ]);

        // отправляем mailer при новом сообщении от админа
        $parent = $ticket->user;
        $notificationService->sendAdminMessage($parent, $message->body);

        // Подгружаем связи для ресурса
        $ticket->load(['user', 'latestMessage']);

        // Отправляем событие
        broadcast(new TicketMessageAdded(new TicketBroadcastResource($message)));
        return redirect()->back();
    }

    public function destroy(Message $message)
    {
        if ($message->user_id === Auth::id()) {
            $message->delete();
        }
        return redirect()->back();
    }
}
