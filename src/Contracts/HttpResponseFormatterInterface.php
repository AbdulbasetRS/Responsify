<?php

namespace Abdulbaset\Responsify\Contracts;

/**
 * Interface for HTTP Response formatting
 */
interface HttpResponseFormatterInterface
{
    /**
     * Convert to Response for Web routes
     *
     * @return \Illuminate\Http\Response
     */
    public function toResponse(): \Illuminate\Http\Response;

    /**
     * Send response directly
     *
     * @return void
     */
    public function send(): void;
}
