<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketReport extends Notification implements ShouldQueue
{
    use Queueable;

    public array $reportData;

    /**
     * Create a new notification instance.
     *
     * @param array $reportData
     * @return void
     */
    public function __construct(array $reportData)
    {
        $this->reportData = $reportData;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable): array
    {
        return ['mail']; // Use mail channel
    }

    /**
     * Get the mail representation of the notification.
     *
     * @return MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('Weekly Ticket Report')
            ->greeting('Hello')
            ->line($this->reportData['reportText']); // Add the report text

        // Add lines for each section of tickets
        if (!empty($this->reportData['updated'])) {
            $mail->line('Tickets updated in the last week:');
            foreach ($this->reportData['updated'] as $ticket) {
                $mail->line("ID: {$ticket->id}, By: {$ticket->user->name}, Title: {$ticket->title}, Status: {$ticket->status}, Updated At: {$ticket->updated_at}");
            }
        } else {
            $mail->line('No tickets were updated in the last week.');
        }

        if (!empty($this->reportData['deleted'])) {
            $mail->line('Tickets deleted in the last week:');
            foreach ($this->reportData['deleted'] as $ticket) {
                $mail->line("ID: {$ticket->id}, By: {$ticket->user->name}, Title: {$ticket->title}, Status: {$ticket->status}, Deleted At: {$ticket->deleted_at}");
            }
        } else {
            $mail->line('No tickets were deleted in the last week.');
        }

        if (!empty($this->reportData['created'])) {
            $mail->line('Tickets created in the last week:');
            foreach ($this->reportData['created'] as $ticket) {
                $mail->line("ID: {$ticket->id}, By: {$ticket->user->name}, Title: {$ticket->title}, Status: {$ticket->status}, Created At: {$ticket->created_at}");
            }
        } else {
            $mail->line('No tickets were created in the last week.');
        }

        return $mail;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray($notifiable): array
    {
        return [
            'reportData' => $this->reportData,
        ];
    }
}
