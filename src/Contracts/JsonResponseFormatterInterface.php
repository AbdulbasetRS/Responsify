<?php

namespace Abdulbaset\Responsify\Contracts;

/**
 * Interface for JSON response formatting
 */
interface JsonResponseFormatterInterface
{
    /**
     * Convert to JsonResponse for API/Controller use
     *
     * @param int $options JSON encoding options
     * @return \Illuminate\Http\JsonResponse
     */
    public function toJson(int $options = 0): \Illuminate\Http\JsonResponse;
}
