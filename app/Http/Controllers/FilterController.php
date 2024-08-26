<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FilterController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();
        $user = auth()->user();
        $sortOrder = $request->input('status', 'newest');
        $statusFilter = $request->input('status_filter', 'all');
        $today = now()->format('Y/m/d');
        $categoryId = $request->input('category_id');

        $ticketsQuery = Ticket::query();

        // Filter by user
        if ($user->isAdmin && $request->filled('user_id')) {
            $ticketsQuery->where('user_id', $request->input('user_id'));
        } elseif (!$user->isAdmin) {
            $ticketsQuery->where('user_id', $user->id);
        }

        // Filter by category
        if ($categoryId) {
            $ticketsQuery->whereHas('categories', function($query) use ($categoryId) {
                $query->where('categories.id', $categoryId);
            });
        }

        // Sort order
        if ($sortOrder === 'oldest') {
            $ticketsQuery->oldest();
        } else {
            $ticketsQuery->latest();
        }

        // Status filter
        switch ($statusFilter) {
            case 'today':
                $ticketsQuery->where('due_date', $today);
                break;

            case 'expired':
                $ticketsQuery->where('due_date', '<', $today);
                break;

            case 'under_process':
                $ticketsQuery->where('due_date', '>', $today);
                break;
        }

        $tickets = $ticketsQuery->get();
        $users = User::all();

        return view('ticket.index', compact('tickets', 'users', 'categories'));
    }

    public function store(Request $request, $ticketId)
    {
        $dueDate = $request->input('date');
        $parsedDate = Carbon::createFromFormat('Y/m/d', $dueDate)->format('Y/m/d');

        $ticket = Ticket::find($ticketId);

        if ($ticket) {
            $ticket->due_date = $parsedDate;
            $ticket->save();

            return response()->json([
                'success' => true,
                'data' => [
                    'due_date' => $ticket->due_date,
                ],
            ]);
        }

        return response()->json(['error' => 'Ticket not found'], 404);
    }
}
