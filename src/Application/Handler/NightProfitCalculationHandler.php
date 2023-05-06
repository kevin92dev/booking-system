<?php

namespace App\Application\Handler;

use App\Domain\Model\Booking;
use App\Application\Command\NightProfitCalculationCommand;
use App\Application\DataTransferObject\NightProfitCalculationDTO;

class NightProfitCalculationHandler
{
    public function handle(NightProfitCalculationCommand $command): NightProfitCalculationDTO
    {
        $bookings = array_map(
            static fn ($item) => Booking::fromArray(
                $item
            ),
            $command->getBookingsRequest()
        );

        return new NightProfitCalculationDTO(
            Booking::calculateNightProfit($bookings)
        );
    }
}