<?php

declare(strict_types=1);

namespace App\Tests\Application\Handler;

use Faker\Factory;
use App\Domain\Model\Booking;
use PHPUnit\Framework\TestCase;
use App\Application\Handler\MaximizeProfitHandler;
use App\Application\Command\MaximizeProfitCommand;
use App\Application\DataTransferObject\MaximizeProfitDTO;

class MaximizeProfitHandlerTest extends TestCase
{
    public function testHandle()
    {
        $firstBookingRequest = [
            'request_id' => Factory::create()->word(),
            'check_in' => Factory::create()->date(),
            'nights' => Factory::create()->randomDigitNotZero(),
            'selling_rate' => Factory::create()->numberBetween(50, 300),
            'margin' => Factory::create()->numberBetween(10, 90)
        ];

        $secondBookingRequest = [
            'request_id' => Factory::create()->word(),
            'check_in' => Factory::create()->date(),
            'nights' => Factory::create()->randomDigitNotZero(),
            'selling_rate' => Factory::create()->numberBetween(50, 300),
            'margin' => Factory::create()->numberBetween(10, 90)
        ];

        $thirdBookingRequest = [
            'request_id' => Factory::create()->word(),
            'check_in' => Factory::create()->date(),
            'nights' => Factory::create()->randomDigitNotZero(),
            'selling_rate' => Factory::create()->numberBetween(50, 300),
            'margin' => Factory::create()->numberBetween(10, 90)
        ];

        $bookingsRequest = [$firstBookingRequest, $secondBookingRequest, $thirdBookingRequest];

        $command = new MaximizeProfitCommand($bookingsRequest);
        $handler = new MaximizeProfitHandler();

        $expectedResult = new MaximizeProfitDTO(
            Booking::calculateBestProfitCombination([
                Booking::fromArray($firstBookingRequest),
                Booking::fromArray($secondBookingRequest),
                Booking::fromArray($thirdBookingRequest),
            ])
        );

        $result = $handler->handle($command);

        $this->assertEquals($expectedResult, $result);
        $this->assertEquals($expectedResult->getBookingCombination(), $expectedResult->jsonSerialize());
    }
}
