<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Models\Category;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\TicketUpdatedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    // Displays a list of tickets, depending on the user's role
    public function index(Request $request)
    {
        $user = $request->user();
        $ticketsQuery = Ticket::query();

        // If the user is an admin, show all tickets
        if ($user->isAdmin) {
            $tickets = Ticket::all();
        }
        // Otherwise, show only the tickets belonging to the user
        else {
            $tickets = Ticket::where('user_id', $user->id)->get();
        }

        // Return the index view with the tickets, users, and categories
        return view('ticket.index', [
            'tickets' => $tickets,
            'users' => User::all(),
            'categories' => Category::all(),
        ]);
    }

    // Shows the form to create a new ticket
    public function create()
    {
        $categories = Category::all();
        return view('ticket.create', compact('categories'));
    }

    // Stores a new ticket in the database
    public function store(StoreTicketRequest $request)
    {
        // Log request data for debugging purposes
        \Log::info($request->all());

        // Create the new ticket with the provided data
        $ticket = Ticket::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => auth()->id(),
        ]);

        // Associate categories with the ticket if provided
        if ($request->filled('categories')) {
            $categoryIds = explode(',', $request->input('categories'));
            $ticket->categories()->sync($categoryIds);
        }

        // Store attachment if provided
        if ($request->file('attachment')) {
            $this->storeAttachment($request, $ticket);
        }

        // Redirect to the ticket index page after storing the ticket
        return redirect()->route('ticket.index');
    }

    // Displays a specific ticket
    public function show(Ticket $ticket)
    {
        // Get the names of the categories associated with the ticket
        $categoryNames = $ticket->categories->pluck('name')->toArray();

        // Return the show view with the ticket details and category names
        return view('ticket.show', [
            'ticket' => $ticket,
            'categories' => Category::all(),
            'categoryNames' => $categoryNames,
        ]);
    }

    // Shows the form to edit an existing ticket
    public function edit(Ticket $ticket)
    {
        $users = User::all();
        $categories = Category::all();
        return view('ticket.edit', compact('ticket', 'users', 'categories'));
    }

    // Updates the specified ticket in the database
    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        // Update ticket with the new data from the request
        $data = $request->only(['title', 'description', 'due_date', 'status']);
        $ticket->update($data);

        // Update ticket categories if provided
        if ($request->filled('categories')) {
            $categoryIds = explode(',', $request->input('categories'));
            $ticket->categories()->sync($categoryIds);
        }

        // Handle attachment update
        if ($request->file('attachment')) {
            // Delete the old attachment if it exists
            if ($ticket->attachment && Storage::disk('public')->exists($ticket->attachment)) {
                Storage::disk('public')->delete($ticket->attachment);
            }
            // Store the new attachment
            $this->storeAttachment($request, $ticket);
        }

        // If the user is an admin, allow updating the user assigned to the ticket
        if (auth()->user()->isAdmin && $request->filled('user_id') && $request->input('user_id') != $ticket->user_id) {
            $ticket->update(['user_id' => $request->input('user_id')]);
        }

        // Notify the ticket owner of the update
        $ticket->user->notify(new TicketUpdatedNotification($ticket));

        // Redirect to the ticket index page after updating the ticket
        return redirect()->route('ticket.index');
    }

    // Deletes the specified ticket
    public function destroy(Ticket $ticket)
    {
        // Delete the attachment if it exists
        if ($ticket->attachment && Storage::disk('public')->exists($ticket->attachment)) {
            Storage::disk('public')->delete($ticket->attachment);
        }

        // Delete the ticket itself
        $ticket->delete();

        // Redirect to the ticket index page after deletion
        return redirect()->route('ticket.index');
    }

    // Helper method to store ticket attachment
    protected function storeAttachment(Request $request, Ticket $ticket)
    {
        // Generate a random filename for the attachment
        $extension = $request->file('attachment')->extension();
        $filename = Str::random(25);
        $path = "attachments/{$filename}.{$extension}";

        // Store the attachment in the public disk and update the ticket's attachment path
        Storage::disk('public')->put($path, file_get_contents($request->file('attachment')));
        $ticket->update(['attachment' => $path]);
    }
}
