<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Traits\FirebaseTrait;

class OrderNotification extends Notification
{
    use Queueable , FirebaseTrait;
    protected $receiver, $data;

    public function __construct($order, $reciever)
    {
        $this->receiver = $reciever;
        
        $this->data     = [
            'order_id'    => $order->id,
            'order_num'   => $order->order_num,
            'type'        => 'finish_order' ,
        ];
    }
    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        if ($notifiable->is_notify) {
            $this->sendFcmNotification($notifiable->devices(), $notifiable->is_notify, $this->data, $notifiable->lang);
        }
        return $this->data;
    }
}
