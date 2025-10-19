<?php

namespace Abdulbaset\Responsify\Contracts;

/**
 * Combined interface for all response formatter functionality
 */
interface ResponseFormatterInterface extends
    JsonResponseFormatterInterface,
    ArrayResponseFormatterInterface,
    StringResponseFormatterInterface,
    CollectionResponseFormatterInterface,
    HttpResponseFormatterInterface
{
    // This interface combines all formatter interfaces for convenience
}
