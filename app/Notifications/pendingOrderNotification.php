<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class pendingOrderNotification extends Notification
{
    use Queueable;

    public $order;
   
    public function __construct($order)
    {
       $this->order = $order;
    }

    
    public function via($notifiable)
    {
        return ['database'];
    }

    
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    
    public function toArray($notifiable)
    {
        return [
           'order_id' => $this->order->order_id,
           'user_name' => $this->order->user->id,
           'order_amount' => $this->order->order_amount,
           'status'=>$this->order->status
        ];
    }
}
