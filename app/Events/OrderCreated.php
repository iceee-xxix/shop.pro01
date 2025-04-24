<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class OrderCreated implements ShouldBroadcast
{
    use SerializesModels;

    public $order;

    public function __construct($order)
    {
        $this->order = $order;
        Log::info('📡 Order Event Triggered!', $this->order);
    }

    public function broadcastOn()
    {
        return new Channel('orders');
    }
}
