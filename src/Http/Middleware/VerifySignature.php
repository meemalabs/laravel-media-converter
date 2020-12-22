<?php

namespace Meema\MediaConverter\Http\Middleware;

use Aws\Sns\Message;
use Aws\Sns\MessageValidator;
use Closure;
use Illuminate\Http\Client\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class VerifySignature
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            // Create a message from the post data and validate its signature
            $message = Message::fromRawPostData();

            // Validate the message
            $validator = new MessageValidator();

            if ($validator->isValid($message)) {
                return $next($request);
            }
        } catch (\Exception $e) {
        }

        // If you get this far it means the request is not found
        throw new NotFoundHttpException();
    }
}
