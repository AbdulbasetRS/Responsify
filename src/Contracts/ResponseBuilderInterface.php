<?php

namespace Abdulbaset\Responsify\Contracts;

/**
 * Interface for response builder functionality
 */
interface ResponseBuilderInterface
{
    /**
     * Set custom message
     *
     * @param string $message
     * @return self
     */
    public function message(string $message);

    /**
     * Set custom details
     *
     * @param string $details
     * @return self
     */
    public function details(string $details);

    /**
     * Set response data
     *
     * @param mixed $data
     * @return self
     */
    public function data(mixed $data);

    /**
     * Set language for translations
     *
     * @param string $language
     * @return self
     */
    public function language(string $language);
}
