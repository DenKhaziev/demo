<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DocumentByGrade;
use App\Models\Ticket;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class AdminSidebarController extends Controller
{
    public function index()
    {

        $ticketCounts = Ticket::where('viewed_by_admin', false)->count();
        return Inertia::render('Admin/AdminPersonalAffairs', [
            'ticketCounts' => $ticketCounts
        ]);
    }
}
