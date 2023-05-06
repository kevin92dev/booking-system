<?php

namespace App\Application\Command;

abstract class BookingListRequest
{
    /**
     * @param array $bookingsRequest
     */
    public function __construct(
        private readonly array $bookingsRequest
    ) {}

    /**
     * @return array
     */
    public function getBookingsRequest(): array
    {
        return $this->bookingsRequest;
    }
}