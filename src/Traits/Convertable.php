<?php

namespace Meema\MediaConverter\Traits;

use Meema\MediaConverter\Models\MediaConversion;

trait Convertable
{
    /**
     * Get all of the media items' conversions.
     */
    public function conversions()
    {
        return $this->morphMany(MediaConversion::class, 'model');
    }
}
