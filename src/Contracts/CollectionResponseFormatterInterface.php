<?php

namespace Abdulbaset\Responsify\Contracts;

/**
 * Interface for Collection response formatting
 */
interface CollectionResponseFormatterInterface
{
    /**
     * Convert to Laravel Collection for fluent handling
     *
     * @return \Illuminate\Support\Collection
     */
    public function toCollection(): \Illuminate\Support\Collection;
}
