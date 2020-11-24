<?php

namespace Meema\MediaConvert\Http\Controllers;

use Aws\Sns\Message;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Meema\MediaConvert\Events\ConversionHasCompleted;
use Meema\MediaConvert\Events\ConversionHasError;
use Meema\MediaConvert\Events\ConversionHasInputInformation;
use Meema\MediaConvert\Events\ConversionHasNewWarning;
use Meema\MediaConvert\Events\ConversionHasStatusUpdate;
use Meema\MediaConvert\Events\ConversionIsProgressing;
use Meema\MediaConvert\Events\ConversionQueueHop;

class IncomingWebhookController extends Controller
{
    public function __construct()
    {
        $this->middleware('verify-signature');
    }

    /**
     * @throws \Exception
     */
    public function __invoke()
    {
        $message = json_decode(Message::fromRawPostData()['Message'], true);
        $detail = $message['detail'];
        $status = $detail['status'];

        try {
            $this->fireEventFor($status, $message);
        } catch (\Exception $e) {
            throw new \Exception("unhandled status event: $status");
        }
    }

    /**
     * @param $status
     * @param $message
     * @throws \Exception
     */
    public function fireEventFor($status, $message)
    {
        switch ($status) {
            case 'PROGRESSING':
                event(new ConversionIsProgressing($message));
                break;
            case 'INPUT_INFORMATION':
                event(new ConversionHasInputInformation($message));
                break;
            case 'COMPLETE':
                event(new ConversionHasCompleted($message));
                break;
            case 'STATUS_UPDATE':
                event(new ConversionHasStatusUpdate($message));
                break;
            case 'NEW_WARNING':
                event(new ConversionHasNewWarning($message));
                break;
            case 'QUEUE_HOP':
                event(new ConversionQueueHop($message));
                break;
            case 'ERROR':
                event(new ConversionHasError($message));
                break;
            default:
                throw new \Exception();
        }
    }
}
