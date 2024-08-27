<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FilterController extends Controller
{
    // Displays a filtered and sorted list of tickets based on user inputs
    public function index(Request $request)
    {
        $categories = Category::all();  // Retrieve all categories
        $user = auth()->user();  // Get the currently authenticated user
        $sortOrder = $request->input('status', 'newest');  // Determine the sort order, defaulting to 'newest'
        $statusFilter = $request->input('status_filter', 'all');  // Get the status filter, defaulting to 'all'
        $today = now()->format('Y/m/d');  // Get today's date in 'Y/m/d' format
        $categoryId = $request->input('category_id');  // Get the selected category ID, if any

        $ticketsQuery = Ticket::query();  // Start a query on the Ticket model

        // Filter tickets by user: If admin, filter by specified user ID; otherwise, filter by the current user's ID
        if ($user->isAdmin && $request->filled('user_id')) {
            $ticketsQuery->where('user_id', $request->input('user_id'));
        } elseif (!$user->isAdmin) {
            $ticketsQuery->where('user_id', $user->id);
        }

        // Filter tickets by category, if a category ID is provided
        if ($categoryId) {
            $ticketsQuery->whereHas('categories', function($query) use ($categoryId) {
                $query->where('categories.id', $categoryId);
            });
        }

        // Apply sort order to the tickets query
        if ($sortOrder === 'oldest') {
            $ticketsQuery->oldest();  // Sort by oldest first
        } else {
            $ticketsQuery->latest();  // Sort by newest first (default)
        }

        // Apply status filter to the tickets query
        switch ($statusFilter) {
            case 'today':
                $ticketsQuery->where('due_date', $today);  // Show tickets due today
                break;

            case 'expired':
                $ticketsQuery->where('due_date', '<', $today);  // Show tickets with due dates in the past
                break;

            case 'under_process':
                $ticketsQuery->where('due_date', '>', $today);  // Show tickets with due dates in the future
                break;
        }

        $tickets = $ticketsQuery->get();  // Execute the query and get the results
        $users = User::all();  // Get all users for potential filtering/display purposes

        // Return the view with the filtered and sorted tickets, along with associated users and categories
        return view('ticket.index', compact('tickets', 'users', 'categories'));
    }

    // Stores or updates the due date for a specific ticket
    public function store(Request $request, $ticketId)
    {
        $dueDate = $request->input('date');  // Get the due date from the request
        $parsedDate = Carbon::createFromFormat('Y/m/d', $dueDate)->format('Y/m/d');  // Parse and format the due date

        $ticket = Ticket::find($ticketId);  // Find the ticket by its ID

        if ($ticket) {
            $ticket->due_date = $parsedDate;  // Update the due date for the ticket
            $ticket->save();  // Save the updated ticket to the database

            // Return a JSON response indicating success and the updated due date
            return response()->json([
                'success' => true,
                'data' => [
                    'due_date' => $ticket->due_date,
                ],
            ]);
        }

        // Return a JSON response indicating the ticket was not found
        return response()->json(['error' => 'Ticket not found'], 404);
    }
}

