<?php

namespace App\Notifications;

use App\Models\PermissionRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountRequestResult extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public PermissionRequest $permissionRequest, public string $string)
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
            ->subject("Pedido de alteração de conta")
            ->line("Seu pedido de alteração de conta para {$this->permissionRequest->requested_type} foi {$this->string}.")
            ->action('Checar Agora', url('http://127.0.0.1:8000/profile/'))
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
