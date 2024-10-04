<?php

namespace App\Console\Commands;

use App\Models\Ticket;
use App\Models\User;
use App\Notifications\TicketReport;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class GetTicketsUpdates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get-tickets-updates {AdminReport?} {username?}  {date?} {--mailAddress=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $username = $this->argument('username');
        $adminReport = $this->argument('AdminReport') ? true : false;
        $mailAddress = $this->option('mailAddress') ?? 'admin@example.com';
        var_dump($mailAddress);
        $baseDate = $this->argument('date') ? Carbon::parse($this->argument('date')) : Carbon::now();
        $weekAgo = $baseDate->copy()->subDays(7);

        $query = Ticket::query();

        if ($username) {
            $user = User::where('name', $username)->first();

            if (!$user) {
                $this->error("User '{$username}' not found.");
                return;
            }

            $query->where('user_id', $user->id);
        }

        $tickets = $query->whereBetween('created_at', [$weekAgo, $baseDate])->get();
        $dtickets = Ticket::onlyTrashed()->whereBetween('deleted_at', [$weekAgo, $baseDate]);

        if ($username) {
            $dtickets->where('user_id', $user->id);
        }
        $dtickets = $dtickets->get();

        $updated = $query->whereBetween('updated_at', [$weekAgo, $baseDate])->get();
        $assigned = $query->whereBetween('assigned_at', [$weekAgo, $baseDate])->get();

        $reportText = "Report:";
        $reportText .= "Count of Created: " . count($tickets) . " ";
        $reportText .= "Count of Deleted: " . count($dtickets) . " ";
        $reportText .= "Count of Updated: " . count($updated) . " ";
        $reportText .= "Count of Assigned: " . count($assigned ) . " ";


        $this->info($reportText);


        if ($adminReport) {
            User::all()->each(function (User $user) use ($weekAgo, $baseDate) {
                $userCreatedTicket = $user->createdTicket($weekAgo, $baseDate)->get();
                $userAssignedTicket = $user->assignedTicket($weekAgo, $baseDate)->get();
                $userUpdatedTicket = $user->updatedTicket($weekAgo, $baseDate)->get();
                $userDeletedTicket = Ticket::onlyTrashed()->where('user_id', $user->id)->whereBetween('deleted_at', [$weekAgo, $baseDate])->get();

                $reportText = "User: {$user->name} - Report: ";
                $reportText .= "Count of Created: " . count($userCreatedTicket) . " ";
                $reportText .= "Count of Created: " . count($userAssignedTicket) . " ";
                $reportText .= "Count of Deleted: " . count($userDeletedTicket) . " ";
                $reportText .= "Count of Updated: " . count($userUpdatedTicket) . " ";

                $this->info($reportText);
            });
        }


        if ($updated->isEmpty()) {
            $this->info('No tickets were updated in the last week.');
        } else {
            $this->info('Tickets updated in the last week:');
            foreach ($updated as $ticket) {
                $this->line("ID: {$ticket->id}, By: {$ticket->user->name}, Title: {$ticket->title}, Status: {$ticket->status},Assigned At:  {$ticket->assigned_at}, Updated At: {$ticket->updated_at}");
            }
        }

        if ($dtickets->isEmpty()) {
            $this->info('No tickets were deleted in the last week.');
        } else {
            $this->info('Tickets deleted in the last week:');
            foreach ($dtickets as $ticket) {
                $this->line("ID: {$ticket->id}, By: {$ticket->user->name}, Title: {$ticket->title}, Status: {$ticket->status},Assigned At:  {$ticket->assigned_at}, Deleted At: {$ticket->deleted_at}");
            }
        }

        if ($tickets->isEmpty()) {
            $this->info('No tickets were created in the last week.');
        } else {
            $this->info('Tickets created in the last week:');
            foreach ($tickets as $ticket) {
                $this->line("ID: {$ticket->id}, By: {$ticket->user->name}, Title: {$ticket->title}, Status: {$ticket->status},Assigned At:  {$ticket->assigned_at}, Created At: {$ticket->created_at}");
            }

            $reportData = [
                'reportText' => $reportText,
                'updated' => $updated,
                'deleted' => $dtickets,
                'created' => $tickets,
            ];

          Notification::route('mail', $mailAddress)->notify(new TicketReport($reportData));
        }
    }

}
