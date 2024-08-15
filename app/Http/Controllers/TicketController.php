<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
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
        $user = auth()->user();

        $sortOrder = $request->input('status', 'newest');

        if ($user->isAdmin) {
            if ($request->filled('user_id')) {
                $ticketsQuery = Ticket::where('user_id', $request->input('user_id'));
            } else {
                $ticketsQuery = Ticket::query();
            }
        } else {
            $ticketsQuery = Ticket::where('user_id', $user->id);
        }

        if ($sortOrder === 'oldest') {
            $ticketsQuery->oldest();
        } else {
            $ticketsQuery->latest();
        }

        $tickets = $ticketsQuery->get();

        $users = User::all();

        return view('ticket.index', compact('tickets', 'users'));
    }




    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('ticket.create');
    }


    public function store(StoreTicketRequest $request)
    {


        $ticket = Ticket::create([
            'title' => $request->title,
            'description'=>$request->description,
            'user_id'=>auth()->id()
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



        $ticket->update($request->except('attachment'));

        if ($request->has('status')) {
            //$user = User::find($ticket->user_id);
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
