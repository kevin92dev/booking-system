<?php

declare(strict_types=1);

namespace App\Tests\Application\Handler;

use Faker\Factory;
use App\Domain\Model\Booking;
use PHPUnit\Framework\TestCase;
use App\Application\Handler\NightProfitCalculationHandler;
use App\Application\Command\NightProfitCalculationCommand;
use App\Application\DataTransferObject\NightProfitCalculationDTO;

class NightProfitCalculationHandlerTest extends TestCase
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

        $command = new NightProfitCalculationCommand($bookingsRequest);
        $handler = new NightProfitCalculationHandler();

        $expectedResult = new NightProfitCalculationDTO(
            Booking::calculateNightProfit([
                Booking::fromArray($firstBookingRequest),
                Booking::fromArray($secondBookingRequest),
                Booking::fromArray($thirdBookingRequest),
            ])
        );

        $result = $handler->handle($command);

        $this->assertEquals($expectedResult, $result);
        $this->assertEquals($expectedResult->getBookingNightAverage(), $expectedResult->jsonSerialize());
    }
}
