<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\SubscriptionRenewal;

class SubscriptionReminder extends Notification
{
    use Queueable;

    protected $renewal;

    public function __construct(SubscriptionRenewal $renewal)
    {
        $this->renewal = $renewal;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Subscription Renewal Reminder')
                    ->greeting('Hello!')
                    ->line("Your subscription for {$this->renewal->hospital->name} will renew on {$this->renewal->renewal_date->format('M j, Y')}.")
                    ->line('Please ensure your payment method is up to date to avoid service interruption.')
                    ->action('Renew Now', url("/hospital/subscription/renew/{$this->renewal->id}"))
                    ->line('Thank you for choosing us!');
    }

    public function toArray($notifiable)
    {
        return [
            'renewal_id' => $this->renewal->id,
            'message' => "Your subscription for {$this->renewal->hospital->name} will renew on {$this->renewal->renewal_date->format('M j, Y')}.",
        ];
    }
}