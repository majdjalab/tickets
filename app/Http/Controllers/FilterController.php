<?php
namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FilterController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $sortOrder = $request->input('status', 'newest');
        $statusFilter = $request->input('status_filter', 'all');
        $today = now()->format('Y/m/d');

        $ticketsQuery = Ticket::query();

        if ($user->isAdmin && $request->filled('user_id')) {
            $ticketsQuery->where('user_id', $request->input('user_id'));
        } elseif (!$user->isAdmin) {
            $ticketsQuery->where('user_id', $user->id);
        }

        // Sorting
        if ($sortOrder === 'oldest') {
            $ticketsQuery->oldest();
        } else {
            $ticketsQuery->latest();
        }

        if ($statusFilter === 'today') {
            $ticketsQuery->where('due_date', $today);
        } elseif ($statusFilter === 'expired') {
            $ticketsQuery->where('due_date', '<', $today);
        } elseif ($statusFilter === 'under_process') {
            $ticketsQuery->where('due_date', '>', $today);
        }

        $tickets = $ticketsQuery->get();
        $users = User::all();

        return view('ticket.index', compact('tickets', 'users'));
    }

    public function store(Request $request, $ticketId)
    {


        $dueDate = $request->input('date');

        $parsedDate = Carbon::createFromFormat('Y/m/d', $dueDate)->format('Y/m/d');

        $ticket = Ticket::find($ticketId);
        if ($ticket) {
            $ticket->due_date = $parsedDate;
            $ticket->save();
        } else {
            return response()->json(['error' => 'Ticket not found'], 404);
        }


        return response()->json([
            'success' => true,
            'data' => [
                'due_date' => $ticket->due_date,
            ]
        ]);
    }

}
