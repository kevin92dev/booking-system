<?php

namespace App\Infrastructure\UI\Middleware;

use League\Tactician\Middleware;
use Symfony\Component\HttpFoundation\Response;
use App\Infrastructure\Service\InvalidCommandExceptionParser;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use League\Tactician\Bundle\Middleware\InvalidCommandException;

class ExceptionMiddleware implements Middleware
{
    private InvalidCommandExceptionParser $exceptionParser;

    /**
     * ExceptionMiddleware constructor.
     */
    public function __construct(InvalidCommandExceptionParser $exceptionParser)
    {
        $this->exceptionParser = $exceptionParser;
    }

    public function execute($command, callable $next)
    {
        try {
            return $next($command);
        } catch (InvalidCommandException $e) {
            throw new BadRequestHttpException(
                $this->exceptionParser->parseException($e)[0] ?? '', null, Response::HTTP_BAD_REQUEST
            );
        }
    }
}
