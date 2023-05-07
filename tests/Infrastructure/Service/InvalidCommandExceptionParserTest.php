<?php

declare(strict_types=1);

namespace App\Tests\Infrastructure\Service;

use App\Application\Command\MaximizeProfitCommand;
use App\Infrastructure\Service\InvalidCommandExceptionParser;
use Faker\Factory;
use League\Tactician\Bundle\Middleware\InvalidCommandException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Contracts\Translation\TranslatorInterface;

class InvalidCommandExceptionParserTest extends TestCase
{
    private TranslatorInterface $translator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->translator = $this->createMock(TranslatorInterface::class);
    }

    public function testParseException(): void
    {
        $message = Factory::create()->word();

        $violationList = ConstraintViolationList::createFromMessage($message);

        $exception = InvalidCommandException::onCommand(
            new MaximizeProfitCommand([]),
            $violationList
        );

        $this->translator
            ->expects($this->once())
            ->method('trans')
            ->with($message)
            ->willReturn($message);

        $parser = new InvalidCommandExceptionParser($this->translator);

        $parsedExceptionMessage = $parser->parseException($exception);

        self::assertEquals($message, $parsedExceptionMessage[0]);
    }
}
