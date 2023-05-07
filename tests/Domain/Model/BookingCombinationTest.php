<?php

declare(strict_types=1);

namespace App\Tests\Domain\Model;

use App\Domain\Model\Booking;
use PHPUnit\Framework\TestCase;
use App\Domain\Model\BookingCombination;

class BookingCombinationTest extends TestCase
{
    public function testConstructorAndGetter(): void
    {
        $bookings = [
            BookingStub::default(),
            BookingStub::default(),
            BookingStub::default()
        ];

        $bookingCombination = new BookingCombination($bookings);

        self::assertEquals($bookings, $bookingCombination->getBookings());
    }

    public function testAppendBookingMethod(): void
    {
        $bookings = [
            BookingStub::default(),
            BookingStub::default()
        ];

        $extraBooking = BookingStub::default();

        $bookingCombination = new BookingCombination($bookings);
        $bookingCombination->appendBooking($extraBooking);

        self::assertEquals(
            array_merge($bookings, [$extraBooking]),
            $bookingCombination->getBookings()
        );
    }

    public function testCalculateCombinationProfit(): void
    {
        $bookings = [
            BookingStub::default(),
            BookingStub::default(),
            BookingStub::default()
        ];

        $bookingCombination = new BookingCombination($bookings);

        $expectedTotalProfit = 0;

        foreach ($bookings as $booking) {
            $expectedTotalProfit += $booking->totalProfit();
        }

        self::assertEquals($expectedTotalProfit, $bookingCombination->calculateCombinationProfit());
    }

    public function testJsonSerializing(): void
    {
        $bookings = [
            BookingStub::default(),
            BookingStub::default()
        ];

        $bookingCombination = new BookingCombination($bookings);

        $nightProfit = Booking::calculateNightProfit($bookings);

        $expectedResult = array_merge(
            [
                "request_ids" => [
                    $bookings[0]->getRequestId(),
                    $bookings[1]->getRequestId()
                ],
                "total_profit" => $bookingCombination->calculateCombinationProfit(),
            ],
            $nightProfit->jsonSerialize()
        );

        self::assertEquals($expectedResult, $bookingCombination->jsonSerialize());
    }
}
