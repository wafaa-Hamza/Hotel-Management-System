<?php

namespace App\Notifications;


use Illuminate\Bus\Queueable;

use Illuminate\Support\Facades\Mail;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class LoginNotification extends Notification
{
    use Queueable;
    public $message;
    public $subject;
    public $fromEmail;
    public $mailer;
    public $tenant_id;
    
    /**
     * Create a new notification instance.
     */

    public function __construct()
    {
        $this->message ='You Just Logged In';
        $this->subject ='New Logging In';
        $this->fromEmail ='hello@example.com';
        $this->mailer ='smtp';
    
       
    }

   
    
    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
     return ['mail'];
     // return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
      
        return (new MailMessage)
                    // ->line('The introduction to the notification.')
                    // ->action('Notification Action', url('/'))
                    // ->line('Thank you for using our application!');
                    ->mailer('smtp')
                    ->subject($this->subject)
                    ->greeting('Welcome '.$notifiable->tenantname)
                    ->line($this->message);
                   // ->line($this->tenant_id);
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
