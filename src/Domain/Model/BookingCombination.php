<?php

namespace App\Domain\Model;

class BookingCombination implements \JsonSerializable
{
    public function __construct(
        private array $bookings
    ) {}

    public function appendBooking(Booking $booking): void
    {
        $this->bookings[] = $booking;
    }

    /**
     * @return array
     */
    public function getBookings(): array
    {
        return $this->bookings;
    }

    public function calculateCombinationProfit(): float
    {
        return array_sum(array_map(static function(Booking $booking) {
            return $booking->totalProfit();
        }, $this->bookings));
    }

    public function jsonSerialize(): array
    {
        $requestIds = array_map(static function(Booking $booking) {
            return $booking->getRequestId();
        }, $this->bookings);

        $nightProfit = Booking::calculateNightProfit($this->bookings);

        return array_merge(
            [
                "request_ids" => $requestIds,
                "total_profit" => $this->calculateCombinationProfit()
            ],
            $nightProfit->jsonSerialize()
        );
    }
}