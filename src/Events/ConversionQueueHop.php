<?php

namespace Meema\MediaConverter\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Meema\MediaConverter\Models\MediaConversion;

class ConversionQueueHop
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    /**
     * Create a new event instance.
     *
     * @param  $message
     */
    public function __construct($message)
    {
        $this->message = $message;

        if (
            config('media-converter.track_media_conversions')
            && in_array('queue_hop', config('media-converter.statuses_to_track'))
        ) {
            MediaConversion::createActivity($message);
        }
    }
}
