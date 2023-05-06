<?php

namespace App\Application\Handler;

use App\Domain\Model\Booking;
use App\Application\Command\MaximizeProfitCommand;
use App\Application\DataTransferObject\MaximizeProfitDTO;

class MaximizeProfitHandler
{
    public function handle(MaximizeProfitCommand $command): MaximizeProfitDTO
    {
        $bookings = array_map(
            static fn ($item) => Booking::fromArray(
                $item
            ),
            $command->getBookingsRequest()
        );

        return new MaximizeProfitDTO(
            Booking::calculateBestProfitCombination($bookings)
        );
    }
}