<?php

namespace Abdulbaset\Responsify\Contracts;

/**
 * Interface for string response formatting
 */
interface StringResponseFormatterInterface
{
    /**
     * Convert to JSON string for logging/external systems
     *
     * @return string
     */
    public function toJsonString(): string;

    /**
     * Convert to string for debug/echo
     *
     * @return string
     */
    public function __toString(): string;
}
