<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class TransactionNotification extends Notification
{
    use Queueable;

    protected $transactionDetails;

    public function __construct($transactionDetails)
    {
        $this->transactionDetails = $transactionDetails;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('Your transaction was successful.')
                    ->line('Transaction Details: ' . $this->transactionDetails)
                    ->line('Thank you for using our application!');
    }
}
