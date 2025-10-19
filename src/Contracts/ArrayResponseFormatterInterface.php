<?php

namespace Abdulbaset\Responsify\Contracts;

/**
 * Interface for array response formatting
 */
interface ArrayResponseFormatterInterface
{
    /**
     * Convert to array for internal/testing use
     *
     * @return array
     */
    public function toArray(): array;
}
