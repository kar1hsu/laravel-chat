<?php

namespace App\Events;

use App\Services\WorkerManService;
use GatewayWorker\Lib\Gateway;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Worker
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }

    public static function onWorkerStart($businessWorker)
    {
        echo "onWorkerStart\r\n";
    }

    public static function onConnect($client_id)
    {
        echo "onConnect\r\n";
    }

    public static function onWebSocketConnect($client_id, $data)
    {
        echo "onWebSocketConnect\r\n";
    }

    public static function onMessage($client_id, $message)
    {
        (new WorkerManService())->sendMessage($client_id, $message);
        echo "onMessage\r\n";
    }

    public static function onClose($client_id)
    {
        echo "onClose\r\n";
    }
}
