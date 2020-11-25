<?php

namespace Meema\MediaConvert\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasMediaConversions
{
    public function media(): MorphMany
    {
        return $this->morphMany(config('media-library.media_model'), 'model');
    }
}
