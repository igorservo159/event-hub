<?php

namespace App\Notifications;

use App\Models\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewRegistration extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Registration $registration)
    {
        //
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
    {
        return (new MailMessage)
            ->subject("Inscrição do evento {$this->registration->event->title}")
            ->line('Sua inscrição foi criada com sucesso, porém está aguardando o pagamento.')
            ->action('Pagar Agora', url('http://127.0.0.1:8000/payments/' . $this->registration->id))
            ->line('Obrigado por usar nosso aplicativo!');
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
