<?php

namespace App\Domain\Model;

use DateTimeImmutable;

readonly class Booking
{
    private function __construct(
        private string $requestId,
        private DateTimeImmutable $checkIn,
        private int $numberOfNights,
        private int $sellingRate,
        private int $marginPercentage
    ) {
    }

    /**
     * @param array $data
     *
     * @return static
     * @throws \Exception
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['request_id'],
            new DateTimeImmutable($data['check_in']),
            $data['nights'],
            $data['selling_rate'],
            $data['margin']
        );
    }

    /**
     * @param array<Booking> $bookings
     *
     * @return BookingNightAverage
     */
    public static function calculateNightProfit(array $bookings): BookingNightAverage
    {
        $bookingNightProfits = array_map(
            static function (Booking $booking) {
                return $booking->profitPerNight();
            },
            $bookings
        );

        return new BookingNightAverage(
            round(array_sum($bookingNightProfits) / count($bookingNightProfits), 2),
            min($bookingNightProfits),
            max($bookingNightProfits)
        );
    }

    /**
     * @param array<Booking> $bookings
     *
     * @return BookingCombination
     */
    public static function calculateBestProfitCombination(array $bookings): BookingCombination
    {
        $bookingCombination = new BookingCombination([]);

        return self::generateCombinations(
            $bookingCombination,
            $bookings,
            0,
            $bookingCombination
        );
    }

    /**
     * @param BookingCombination $currentCombination
     * @param array              $remainingBookings
     * @param float              $maxProfit
     * @param BookingCombination $bestCombination
     *
     * @return BookingCombination
     */
    private static function generateCombinations(
        BookingCombination $currentCombination,
        array $remainingBookings,
        float $maxProfit,
        BookingCombination $bestCombination
    ): BookingCombination {
        $profit = $currentCombination->calculateCombinationProfit();

        if ($profit > $maxProfit) {
            $maxProfit = $profit;
            $bestCombination = $currentCombination;
        }

        foreach ($remainingBookings as $index => $booking) {
            $overlaps = false;

            foreach ($currentCombination->getBookings() as $existingBooking) {
                if ($booking->itOverlapsWith($existingBooking)) {
                    $overlaps = true;
                    break;
                }
            }

            if (!$overlaps) {
                $newCurrentCombi = $currentCombination;
                $newCurrentCombi->appendBooking($booking);
                $newRemaining = array_slice($remainingBookings, $index + 1);
                self::generateCombinations($newCurrentCombi, $newRemaining, $maxProfit, $bestCombination);
            }
        }

        return $bestCombination;
    }

    private function itOverlapsWith(Booking $booking): bool
    {
        $latestStartDate = max($this->checkIn, $booking->getCheckIn());
        $earlierEndDate = min(
            $this->checkIn->modify('+' . $this->numberOfNights . ' days'),
            $booking->getCheckIn()->modify('+' . $booking->getNumberOfNights() . ' days')
        );

        return ($latestStartDate < $earlierEndDate);
    }

    public function totalProfit(): float
    {
        return $this->sellingRate * ($this->marginPercentage / 100);
    }

    public function profitPerNight(): float
    {
        return $this->totalProfit() / $this->numberOfNights;
    }

    /**
     * @return string
     */
    public function getRequestId(): string
    {
        return $this->requestId;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getCheckIn(): \DateTimeImmutable
    {
        return $this->checkIn;
    }

    /**
     * @return int
     */
    public function getNumberOfNights(): int
    {
        return $this->numberOfNights;
    }

    /**
     * @return int
     */
    public function getSellingRate(): int
    {
        return $this->sellingRate;
    }

    /**
     * @return int
     */
    public function getMarginPercentage(): int
    {
        return $this->marginPercentage;
    }
}