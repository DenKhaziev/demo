<?php

namespace App\Http\Controllers\Admin;

use App\Events\TicketCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\TicketMessage\StoreTicketRequest;
use App\Http\Resources\TicketBroadcastResource;
use App\Models\Message;
use App\Models\Ticket;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class AdminTicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::with('user');
        // Фильтрация по поиску
        if ($request->filled('search')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        $tickets = $query->orderBy('updated_at', 'desc')->paginate(25);

        return Inertia::render('Admin/AdminTickets', [
            'tickets' => $tickets,
            'search' => $request->search
        ]);
    }

    public function toggle(Ticket $ticket)
    {
        $new = $ticket->status === 'open' ? 'closed' : 'open';

        Ticket::withoutTimestamps(function () use ($ticket, $new) {
            $ticket->updateQuietly(['status' => $new]);
        });

        return back();
    }

    public function unreadCount()
    {
        $count = Ticket::where('viewed_by_admin', false)->count();

        return response()->json(['count' => $count]);
    }

    public function markUnread(Ticket $ticket)
    {
        $ticket->update(['viewed_by_admin' => false]);
//        return redirect()->back()->with('success', 'Сообщение отмечено непрочитанным');
    }

    public function markRead(Ticket $ticket)
    {
        if (auth()->user()->is_admin) {
            $ticket->update(['viewed_by_admin' => true]);
        } else {
            $ticket->update(['viewed_by_user' => true]);
        }
        return response()->noContent();
    }

    public function ticketCreate(User $user)
    {
        return Inertia::render('Admin/AdminCreateTicket', [
            'targetUserId' => (int) $user->id,
        ]);
    }

    public function storeTicket(StoreTicketRequest $request, NotificationService $notificationService)
    {
        $data = $request->validated();
        $actorId = auth()->id();
        // Создаём тикет
        $ticket = Ticket::create([
            'user_id' => (int)$data['user_id'],
            'subject' => $data['subject'],
            'status' => 'open',
            'viewed_by_admin' => true,
            'viewed_by_user' => false,
        ]);
        $parent = $ticket->user;

        $message = Message::create([
            'ticket_id' => $ticket->id,
            'user_id' => $actorId,
            'body' => $data['message'],
            'is_admin' => true
        ]);
        $ticket->load(['user', 'latestMessage']);
        $notificationService->sendAdminMessage($parent, $message->body);
        return redirect()->route('messages.index', $ticket->id);
    }
}
