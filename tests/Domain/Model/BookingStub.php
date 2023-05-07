<?php

declare(strict_types=1);

namespace App\Tests\Domain\Model;

use Faker\Factory;
use App\Domain\Model\Booking;
use PHPUnit\Framework\TestCase;

class BookingStub extends TestCase
{
    public static function default(): Booking
    {
        return Booking::fromArray(
            [
                'request_id' => Factory::create()->word(),
                'check_in' => Factory::create()->date(),
                'nights' => Factory::create()->randomDigitNotZero(),
                'selling_rate' => Factory::create()->numberBetween(50, 300),
                'margin' => Factory::create()->numberBetween(10, 90)
            ]
        );
    }

    public static function fromData(
        string $requestId,
        string $checkIn,
        int $nights,
        int $sellingRate,
        int $margin
    ): Booking {
        return Booking::fromArray(
            [
                'request_id' => $requestId,
                'check_in' => $checkIn,
                'nights' => $nights,
                'selling_rate' => $sellingRate,
                'margin' => $margin
            ]
        );
    }
}
