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
    public function index(Request $request)
    {
        $user = $request->user();
        $ticketsQuery = Ticket::query();

        if ($user->isAdmin) {
            $tickets = Ticket::all();
        } else {
            $tickets = Ticket::where('user_id', $user->id)->get();
        }

        return view('ticket.index', [
            'tickets' => $tickets,
            'users' => User::all(),
            'categories' => Category::all(),
        ]);
    }

    public function create()
    {
        $categories = Category::all();
        return view('ticket.create', compact('categories'));
    }

    public function store(StoreTicketRequest $request)
    {
        // Debug to see what is being sent
        \Log::info($request->all());

        $ticket = Ticket::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => auth()->id(),
        ]);

        if ($request->filled('categories')) {
            $categoryIds = explode(',', $request->input('categories'));
            $ticket->categories()->sync($categoryIds);
        }

        if ($request->file('attachment')) {
            $this->storeAttachment($request, $ticket);
        }

        return redirect()->route('ticket.index');
    }





    public function show(Ticket $ticket)
    {
        $ticket->load('categories');

        $categoryNames = $ticket->categories->pluck('name')->toArray();

        return view('ticket.show', [
            'ticket' => $ticket,
            'categories' => Category::all(),
            'categoryNames' => $categoryNames,
        ]);
    }


    public function edit(Ticket $ticket)
    {

        $users = User::all();
        $categories = Category::all();
        return view('ticket.edit', compact('ticket', 'users', 'categories'));
    }

    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        // Optionally log the incoming request data for debugging
        \Log::info('Update Ticket Request Data:', $request->all());

        // Update ticket details with validated request data
        $ticket->update($request->only('title', 'description', 'due_date', 'status'));

        // Handle file attachment if provided
        if ($request->file('attachment')) {
            // Delete existing attachment if it exists
            if ($ticket->attachment) {
                Storage::disk('public')->delete($ticket->attachment);
            }

            // Store the new attachment
            $this->storeAttachment($request, $ticket);
        }

        // Notify the user about the ticket update
        $ticket->user->notify(new TicketUpdatedNotification($ticket));

        // Redirect to the index page
        return redirect()->route('ticket.index');
    }

    public function destroy(Ticket $ticket)
    {
        if ($ticket->attachment && Storage::disk('public')->exists($ticket->attachment)) {
            Storage::disk('public')->delete($ticket->attachment);
        }

        $ticket->delete();
        return redirect()->route('ticket.index');
    }

    protected function storeAttachment(Request $request, Ticket $ticket)
    {
        $extension = $request->file('attachment')->extension();
        $filename = Str::random(25);
        $path = "attachments/{$filename}.{$extension}";
        Storage::disk('public')->put($path, file_get_contents($request->file('attachment')));
        $ticket->update(['attachment' => $path]);
    }
}
