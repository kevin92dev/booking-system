<?php

namespace App\Application\DataTransferObject;

use App\Domain\Model\BookingNightAverage;

readonly class NightProfitCalculationDTO implements \JsonSerializable
{
    public function __construct(
        private BookingNightAverage $bookingNightAverage
    ) {
    }

    /**
     * @return BookingNightAverage
     */
    public function getBookingNightAverage(): BookingNightAverage
    {
        return $this->bookingNightAverage;
    }

    public function jsonSerialize(): BookingNightAverage
    {
        return $this->bookingNightAverage;
    }
}