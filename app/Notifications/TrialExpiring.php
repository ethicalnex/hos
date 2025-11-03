<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Hospital;

class TrialExpiring extends Notification
{
    use Queueable;

    protected $hospital;

    public function __construct(Hospital $hospital)
    {
        $this->hospital = $hospital;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Your Free Trial is Ending')
                    ->greeting('Hello!')
                    ->line("Your free trial for {$this->hospital->name} will end today.")
                    ->line('To continue using our services, please upgrade your subscription.')
                    ->action('Upgrade Plan', url('/hospital/subscription'))
                    ->line('Thank you for choosing us!');
    }

    public function toArray($notifiable)
    {
        return [
            'hospital_id' => $this->hospital->id,
            'message' => "Your free trial for {$this->hospital->name} has ended.",
        ];
    }
}