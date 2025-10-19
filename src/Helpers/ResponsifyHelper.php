<?php

use Abdulbaset\Responsify\Respond;

if (!function_exists('respond')) {
    /**
     * Create a new standardized API response
     *
     * @param int $status HTTP status code (required)
     * @return Respond
     */
    function respond(int $status): Respond
    {
        return Respond::status($status);
    }
}
