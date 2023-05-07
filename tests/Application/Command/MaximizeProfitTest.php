<?php

declare(strict_types=1);

namespace App\Tests\Application\Command;

use App\Tests\Domain\Model\BookingStub;
use PHPUnit\Framework\TestCase;
use App\Application\Command\MaximizeProfitCommand;

class MaximizeProfitTest extends TestCase
{
    public function testGetBookingsRequestReturnsCorrectValue()
    {
        $bookingsRequest = [
            BookingStub::default(),
            BookingStub::default(),
            BookingStub::default()
        ];

        $command = new MaximizeProfitCommand($bookingsRequest);

        $result = $command->getBookingsRequest();

        $this->assertEquals($bookingsRequest, $result);
    }
}
