<?php

namespace Meema\MediaConverter\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Meema\MediaConverter\Models\MediaConversion;

class ConversionHasNewWarning
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
            config('media-converterer.track_media_conversions')
            && in_array('new_warning', config('media-converterer.statuses_to_track'))
        ) {
            MediaConversion::createActivity($message);
        }
    }
}
