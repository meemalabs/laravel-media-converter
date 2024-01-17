<?php

namespace Meema\MediaConverter\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Str;

/**
 * MediaConversion Model.
 *
 * @property int $id
 * @property string $model_type
 * @property int $model_id
 * @property string $job_id
 * @property mixed $message
 * @property string $status
 * @property int|null $percentage_completed
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|MediaConversion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MediaConversion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MediaConversion query()
 * @method static \Illuminate\Database\Eloquent\Builder|MediaConversion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MediaConversion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MediaConversion whereJobId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MediaConversion whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MediaConversion whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MediaConversion whereModelType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MediaConversion wherePercentageCompleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MediaConversion whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MediaConversion whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class MediaConversion extends Model
{
    protected $guarded = [];

    protected $casts = [
        'message' => 'array',
    ];

    public static function createActivity($message)
    {
        $status = Str::lower($message['detail']['status']);

        if ($status === 'complete') {
            $percentage = 100;
        } else {
            $percentage = $message['detail']['jobProgress']['jobPercentComplete'] ?? null;
        }

        $conversion = new MediaConversion();
        $conversion->model_type = config('media-converter.media_model');
        $conversion->model_id = $message['detail']['userMetadata']['model_id'];
        $conversion->job_id = $message['detail']['jobId'];
        $conversion->message = $message;
        $conversion->status = $status;
        $conversion->percentage_completed = $percentage;
        $conversion->save();
    }

    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    public function inputDetails()
    {
        return $this->message['detail']['inputDetails'];
    }

    public function outputGroupDetails()
    {
        return $this->message['detail']['outputGroupDetails'];
    }
}
