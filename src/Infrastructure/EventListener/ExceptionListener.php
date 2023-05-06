<?php

declare(strict_types=1);

namespace App\Infrastructure\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $code = $exception->getCode();
        $event->setResponse(
            new JsonResponse(["message" => $exception->getMessage()], $code === 0 ? 500 : $code)
        );
    }
}
