<?php

namespace App\Listeners;

use App\Events\MyEvent;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class MyListener
{
    public function broadcastOn()
    {
        return new Channel('my-channel');
    }

    public function broadcastWith()
    {
        return [
            'message' => 'Hello, world!'
        ];
    }

    public function handle($event)
    {
        // Handle the event here
    }
}
