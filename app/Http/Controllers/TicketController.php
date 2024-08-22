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

        if ($user->isAdmin ) {
            $tickets = Ticket::all();
        } elseif (!$user->isAdmin) {
            $tickets = Ticket::where('user_id', $user->id)->get();
        }

        return view('ticket.index', [
            'tickets' => $tickets,
            'users' => User::all()
        ]);
    }




    public function create()
    {
        $categories = Category::all();
        return view('ticket.create', compact('categories'));
    }


    public function store(StoreTicketRequest $request)
    {
        $ticket = Ticket::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => auth()->id(),
            'category_id' => $request->category_id, // Save the selected category
        ]);

        if ($request->file('attachment')) {
            $this->storeAttachment($request, $ticket);
        }

        return response()->redirectTo(route('ticket.index'));
    }



    public function show(Ticket $ticket)
    {


        return view('ticket.show', compact('ticket'));
    }


    public function edit(Ticket $ticket)
    {
        $users = User::all();
        return view('ticket.edit', compact('ticket', 'users'));
    }


    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        $ticket->update($request->only('due_date'));

        $ticket->update($request->except('attachment'));

        if ($request->has('status')) {
            $ticket->user->notify(new TicketUpdatedNotification($ticket));
        }

        if ($request->file('attachment')) {
            Storage::disk('public')->delete($ticket->attachment ?? '');
            $this->storeAttachment($request, $ticket);
        }

        return redirect()->route('ticket.index');
    }



    public function destroy(Ticket $ticket)
    {
        $ticket->delete();
        if ($ticket->attachment && Storage::disk('public')->exists($ticket->attachment)) {
            Storage::disk('public')->delete($ticket->attachment);
        }        return redirect(route('ticket.index'));
    }

    protected function storeAttachment($request, $ticket)
    {
        $ext      = $request->file('attachment')->extension();
        $contents = file_get_contents($request->file('attachment'));
        $filename = Str::random(25);
        $path     = "attachments/$filename.$ext";
        Storage::disk('public')->put($path, $contents);
        $ticket->update(['attachment' => $path]);
    }
}
