<?php

namespace App\Application\DataTransferObject;

use App\Domain\Model\BookingCombination;

readonly class MaximizeProfitDTO implements \JsonSerializable
{
    public function __construct(
        private BookingCombination $bookingCombination
    ) {}

    public function jsonSerialize(): BookingCombination
    {
        return $this->bookingCombination;
    }
}