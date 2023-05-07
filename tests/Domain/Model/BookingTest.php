<?php

declare(strict_types=1);

namespace App\Tests\Domain\Model;

use Faker\Factory;
use App\Domain\Model\Booking;
use PHPUnit\Framework\TestCase;
use App\Domain\Model\BookingCombination;
use App\Domain\Model\BookingNightAverage;

class BookingTest extends TestCase
{
    public function testFromArrayConstructorAndGetters(): void
    {
        $requestId = Factory::create()->word();
        $checkIn = Factory::create()->date();
        $nights = Factory::create()->randomDigit();
        $sellingRate = Factory::create()->numberBetween(50, 300);
        $margin = Factory::create()->numberBetween(10, 90);

        $booking = Booking::fromArray(
            [
                'request_id' => $requestId,
                'check_in' => $checkIn,
                'nights' => $nights,
                'selling_rate' => $sellingRate,
                'margin' => $margin
            ]
        );


        self::assertEquals($requestId, $booking->getRequestId());
        self::assertEquals(new \DateTimeImmutable($checkIn), $booking->getCheckIn());
        self::assertEquals($nights, $booking->getNumberOfNights());
        self::assertEquals($sellingRate, $booking->getSellingRate());
        self::assertEquals($margin, $booking->getMarginPercentage());
    }

    public function testTotalProfit(): void
    {
        $booking = BookingStub::default();

        $expectedProfit = $booking->getSellingRate() * ($booking->getMarginPercentage() / 100);

        self::assertEquals($expectedProfit, $booking->totalProfit());
    }

    public function testNightProfitCalculation(): void
    {
        $firstBooking = BookingStub::default();
        $secondBooking = BookingStub::default();
        $thirdBooking = BookingStub::default();
        $fourthBooking = BookingStub::default();

        $bookings = [$firstBooking, $secondBooking, $thirdBooking, $fourthBooking];

        $bookingProfits = [
            $firstBooking->profitPerNight(),
            $secondBooking->profitPerNight(),
            $thirdBooking->profitPerNight(),
            $fourthBooking->profitPerNight()
        ];

        $totalProfit = array_sum($bookingProfits);

        $averageProfit = round($totalProfit / count($bookings), 2);

        $expectedResult = new BookingNightAverage(
            $averageProfit,
            min($bookingProfits),
            max($bookingProfits)
        );

        $result = Booking::calculateNightProfit($bookings);

        self::assertEquals($expectedResult, $result);
    }

    public function testBestProfitCombinationCalculation(): void
    {
        $firstBooking = BookingStub::fromData(
            "bookata_XY123",
            "2020-01-01",
            5,
            200,
            20
        );
        $secondBooking = BookingStub::fromData(
            "kayete_PP234",
            "2020-01-04",
            4,
            156,
            5
        );
        $thirdBooking = BookingStub::fromData(
            "atropote_AA930",
            "2020-01-04",
            4,
            150,
            6
        );
        $fourthBooking = BookingStub::fromData(
            "acme_AAAAA",
            "2020-01-10",
            4,
            160,
            30
        );

        $result = Booking::calculateBestProfitCombination([
            $firstBooking,
            $secondBooking,
            $thirdBooking,
            $fourthBooking
        ]);

        $expectedResult = new BookingCombination([
            $firstBooking,
            $fourthBooking
        ]);

        self::assertEquals($expectedResult, $result);
    }
}
