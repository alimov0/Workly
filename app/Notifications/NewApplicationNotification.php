<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewApplicationNotification extends Notification
{
    use Queueable;

    public $application;

    public function __construct()
    {
        $this->application = $application;
    }

    public function via(object $notifiable): array
    {
        return ['mail',  'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
        ->subject('Yangi Ariza: ' . $this->application->vacancy->title)
        ->line($this->application->user->name . ' sizning vakansiyangizga ariza yubordi')
        ->action('Arizani Ko\'rish', url('/employer/applications/'.$this->application->id))
        ->line('Ish qidiruvchi: ' . $this->application->user->email);
    }
    public function toArray(object $notifiable): array
    {
        return [
            'vacancy_id' => $this->application->vacancy_id,
            'application_id' => $this->application->id,
            'applicant_name' => $this->application->user->name,
            'message' => 'Yangi ariza: ' . $this->application->vacancy->title,
        ];
    }
}
