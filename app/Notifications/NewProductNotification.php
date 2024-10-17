<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewProductNotification extends Notification
{
    use Queueable;

    protected $product;
    
    public function __construct($product)
    {
        $this->product = $product;
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
            
                'product_id'=>$this->product->id,
                'product_name' => $this->product->product_name,
        ];
    }
}
