<?php

namespace Meema\MediaConvert\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class MediaConversion extends Model
{
    protected $table = 'media_conversions';

    protected $guarded = [];

    protected $casts = [
        'job_settings' => 'array',
        'message' => 'array',
    ];

    public function model(): MorphTo
    {
        return $this->morphTo();
    }
}
