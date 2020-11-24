<?php

namespace Meema\MediaConvert\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ConversionIsProgressing
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    /**
     * Create a new event instance.
     *
     * @param $message
     */
    public function __construct($message)
    {
        $this->message = $message;
    }
}
