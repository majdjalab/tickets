<?php
namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FilterController extends Controller
{
    public function store(Request $request, $ticketId)
    {
        // Retrieve the date from the request in 'YYYY/MM/DD' format
        $dueDate = $request->input('date');

        // Convert the date to 'Y-m-d' format if needed (which is the default date format in Laravel)
        $parsedDate = Carbon::createFromFormat('Y/m/d', $dueDate)->format('Y/m/d');

        // Find the ticket and update its due date
        $ticket = Ticket::find($ticketId);
        if ($ticket) {
            $ticket->due_date = $parsedDate; // Save as 'YYYY-MM-DD' in the database
            $ticket->save();
        } else {
            return response()->json(['error' => 'Ticket not found'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'due_date' => $ticket->due_date, // Return in 'YYYY-MM-DD' format
            ]
        ]);
    }
}

