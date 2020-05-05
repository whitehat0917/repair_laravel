<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use SalamWaddah\Mandrill\MandrillChannel;
use SalamWaddah\Mandrill\MandrillMessage;

class PurchaseSuccessful extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($subject,$template,$repair_id,$problem)
    {
        $this->subject = $subject;
        $this->template = $template;
        $this->repair_id = $repair_id;
        $this->problem = $problem;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', MandrillChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

    public function toMandrill($notifiable)
    {
        return (new MandrillMessage())
        ->addTo($notifiable->email)
        ->subject($this->subject)
        ->templateName($this->template)
        ->content([
            'user'=>$notifiable->email,
            'repairid'=>$this->repair_id,
            'problems'=>$this->problem
        ]);
    }
}
