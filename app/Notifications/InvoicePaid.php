<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvoicePaid extends Notification
{
    use Queueable;
    private $invoice_id;

    /**
     * Create a new notification instance.
     */
    public function __construct(int $invoice_id)
    {
        $this->invoice_id=$invoice_id;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {           $url = 'http://127.0.0.1:8000/InvoicesDetails/'.$this->invoice_id;


        return (new MailMessage)
                    ->subject('new invoice')
                    ->line('The introduction to the notification.') //ep 20d9i9a 28 kefah tati approvation lel gmail tak bech yeb3th notif msg
                    ->action('Show invoice', $url)
                    ->line('Thank you for using Zarzour application !');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
