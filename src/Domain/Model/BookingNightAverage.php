<?php

namespace App\Domain\Model;

readonly class BookingNightAverage implements \JsonSerializable
{
    public function __construct(
        private float $averageNightProfit,
        private float $minimumNightProfit,
        private float $maximumNightProfit
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            "avg_night" => $this->averageNightProfit,
            "min_night" => $this->minimumNightProfit,
            "max_night" => $this->maximumNightProfit
        ];
    }
}