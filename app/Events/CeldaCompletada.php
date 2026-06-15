<?php

namespace App\Events;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CeldaCompletada implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $code;
    public $coordenada;
    
    public function __construct($code, $coordenada) {
        $this->code = $code;
        $this->coordenada = $coordenada;
    }

    public function broadcastOn() {
        return new Channel('sala.' . $this->code);
    }

    public function broadcastAs(): string {
        return 'celda.completada';
    }
}