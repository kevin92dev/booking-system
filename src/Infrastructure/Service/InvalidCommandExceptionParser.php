<?php

namespace App\Infrastructure\Service;

use Symfony\Contracts\Translation\TranslatorInterface;
use League\Tactician\Bundle\Middleware\InvalidCommandException;

class InvalidCommandExceptionParser
{
    private TranslatorInterface $translator;

    /**
     * InvalidCommandExceptionParser constructor.
     *
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param InvalidCommandException $exception
     *
     * @return array
     */
    public function parseException(InvalidCommandException $exception): array
    {
        $message = [];

        foreach ($exception->getViolations() as $violation) {
            if ('This field is missing.' === $violation->getMessage()) {
                $fieldName = $violation->getParameters()['{{ field }}'];
                $fieldName = str_replace('"', "'", $fieldName);
                $message[] = 'Error! Field '.$fieldName.' is mandatory';
                continue;
            }

            $message[] = $this->translator->trans($violation->getMessage());
        }

        return $message;
    }
}
