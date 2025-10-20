<?php

namespace Abdulbaset\Responsify;

use Abdulbaset\Responsify\Contracts\ResponseBuilderInterface;
use Abdulbaset\Responsify\Contracts\ResponseFormatterInterface;
use Abdulbaset\Responsify\Enums\Language;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;

/**
 * Responsify - A Laravel package for standardized API responses
 *
 * This class provides a fluent interface for creating standardized API responses
 * with support for multiple languages and various output formats.
 */
class Respond implements ResponseBuilderInterface, ResponseFormatterInterface
{
    /**
     * The HTTP status code
     */
    protected int $status;

    /**
     * Custom message override
     */
    protected ?string $customMessage = null;

    /**
     * Custom details override
     */
    protected ?string $customDetails = null;

    /**
     * Response data
     */
    protected mixed $data = [];

    /**
     * Language enum instance
     */
    protected ?Language $languageEnum = null;

    /**
     * Create a new Respond instance with status code
     *
     * @param  int  $status  HTTP status code (required)
     */
    public static function status(int $status): self
    {
        $instance = new self;
        $instance->status = $status;

        return $instance;
    }

    /**
     * Set custom message
     */
    public function message(string $message): self
    {
        $this->customMessage = $message;

        return $this;
    }

    /**
     * Set custom details
     */
    public function details(string $details): self
    {
        $this->customDetails = $details;

        return $this;
    }

    /**
     * Set response data
     */
    public function data(mixed $data): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Set language for translations
     *
     * @param  string  $language  Language code (en, ar, de, fr, es, it)
     */
    public function language(string $language): self
    {
        if (Language::isSupported($language)) {
            $this->languageEnum = Language::fromCode($language);
        }

        return $this;
    }

    /**
     * Get the current language, with fallback logic
     */
    protected function getLanguage(): string
    {
        // First priority: manually set language
        if ($this->languageEnum) {
            return $this->languageEnum->value;
        }

        // Second priority: app locale
        $appLocale = Config::get('app.locale');
        if ($appLocale && Language::isSupported($appLocale)) {
            return $appLocale;
        }

        // Third priority: config default language
        $configLanguage = Config::get('responsify.language');
        if ($configLanguage && Language::isSupported($configLanguage)) {
            return $configLanguage;
        }

        // Fallback to English
        return Language::ENGLISH->value;
    }

    protected function translate(string $key, string $fallback): string
    {
        $language = $this->getLanguage();
        $translation = __($key, [], $language);

        return $translation === $key ? $fallback : $translation;
    }

    /**
     * Get translated message for the current status
     */
    protected function getMessage(): string
    {
        if ($this->customMessage) {
            return $this->customMessage;
        }

        return $this->translate(
            "responsify::messages.{$this->status}.message",
            'Unknown Status'
        );
    }

    /**
     * Get translated details for the current status
     */
    protected function getDetails(): string
    {
        if ($this->customDetails) {
            return $this->customDetails;
        }

        return $this->translate(
            "responsify::messages.{$this->status}.details",
            'No additional details available'
        );
    }

    /**
     * Get the response data
     */
    protected function getData(): mixed
    {
        return $this->data ?? [];
    }

    /**
     * Build the response array
     */
    protected function buildResponse(): array
    {
        return [
            'status' => $this->status,
            'message' => $this->getMessage(),
            'details' => $this->getDetails(),
            'data' => $this->getData(),
        ];
    }

    /**
     * Convert response to array (internal/testing use)
     */
    public function toArray(): array
    {
        return $this->buildResponse();
    }

    /**
     * Convert response to JSON string (logging/external systems)
     */
    public function toJsonString(): string
    {
        return json_encode($this->buildResponse(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    /**
     * Convert response to Laravel Collection (fluent handling)
     */
    public function toCollection(): Collection
    {
        return collect($this->buildResponse());
    }

    /**
     * Convert response to JsonResponse (API/Controller use)
     *
     * @param  int  $options  JSON encoding options
     */
    public function toJson(int $options = 0): JsonResponse
    {
        return new JsonResponse($this->buildResponse(), $this->status, [], $options);
    }

    /**
     * Convert response to Response (Web routes)
     */
    public function toResponse(): Response
    {
        return new Response(
            $this->toJsonString(),               // content as JSON string
            $this->status,                       // HTTP status code
            ['Content-Type' => 'application/json'] // headers
        );
    }

    /**
     * Send response directly (direct output)
     */
    public function send(): void
    {
        echo $this->toJsonString();
    }

    /**
     * Convert to string (debug/echo)
     */
    public function __toString(): string
    {
        return $this->toJsonString();
    }
}
