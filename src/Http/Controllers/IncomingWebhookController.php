<?php

namespace Meema\MediaConvert\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Meema\MediaConvert\Events\MediaConversionHasCompleted;
use Meema\MediaConvert\Events\MediaConversionHasError;
use Meema\MediaConvert\Events\MediaConversionHasInputInformation;
use Meema\MediaConvert\Events\MediaConversionIsProgressing;

class IncomingWebhookController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @throws \Exception
     */
    public function __invoke(Request $request)
    {
        $message = json_decode($request->getContent(), true)['Message'];
        $detail = json_decode($message, true)['detail'];
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
                event(new MediaConversionIsProgressing($message));
                break;
            case 'INPUT_INFORMATION':
                event(new MediaConversionHasInputInformation($message));
                break;
            case 'COMPLETE':
                event(new MediaConversionHasCompleted($message));
                break;
            case 'ERROR':
                event(new MediaConversionHasError($message));
                break;
            default:
                throw new \Exception();
        }
    }
}
