<?php

namespace Abdulbaset\Responsify;

use Abdulbaset\Responsify\Contracts\ResponseBuilderInterface;
use Abdulbaset\Responsify\Contracts\ResponseFormatterInterface;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;

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
     * Language code
     */
    protected ?string $language = null;

    /**
     * Supported languages
     */
    protected array $supportedLanguages = ['en', 'ar', 'de', 'fr', 'es', 'it'];

    /**
     * Create a new Respond instance with status code
     *
     * @param int $status HTTP status code (required)
     * @return self
     */
    public static function status(int $status): self
    {
        $instance = new self();
        $instance->status = $status;
        return $instance;
    }

    /**
     * Set custom message
     *
     * @param string $message
     * @return self
     */
    public function message(string $message): self
    {
        $this->customMessage = $message;
        return $this;
    }

    /**
     * Set custom details
     *
     * @param string $details
     * @return self
     */
    public function details(string $details): self
    {
        $this->customDetails = $details;
        return $this;
    }

    /**
     * Set response data
     *
     * @param mixed $data
     * @return self
     */
    public function data(mixed $data): self
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Set language for translations
     *
     * @param string $language Language code (en, ar, de, fr, es, it)
     * @return self
     */
    public function language(string $language): self
    {
        if (in_array($language, $this->supportedLanguages)) {
            $this->language = $language;
        }
        return $this;
    }

    /**
     * Get the current language, with fallback logic
     *
     * @return string
     */
    protected function getLanguage(): string
    {
        // First priority: manually set language
        if ($this->language) {
            return $this->language;
        }

        // Second priority: config default language
        $configLanguage = Config::get('responsify.language');
        if ($configLanguage && in_array($configLanguage, $this->supportedLanguages)) {
            return $configLanguage;
        }

        // Third priority: app locale
        $appLocale = Config::get('app.locale');
        if ($appLocale && in_array($appLocale, $this->supportedLanguages)) {
            return $appLocale;
        }

        // Fallback to English
        return 'en';
    }

    /**
     * Get translated message for the current status
     *
     * @return string
     */
    protected function getMessage(): string
    {
        if ($this->customMessage) {
            return $this->customMessage;
        }

        $language = $this->getLanguage();

        // Try to load translation from the language file
        $translationPath = __DIR__ . "/lang/{$language}/messages.php";

        if (file_exists($translationPath)) {
            $translations = include $translationPath;
            if (isset($translations[$this->status]['message'])) {
                return $translations[$this->status]['message'];
            }
        }

        // Fallback to English if current language file doesn't have the status
        if ($language !== 'en') {
            $englishPath = __DIR__ . "/lang/en/messages.php";
            if (file_exists($englishPath)) {
                $translations = include $englishPath;
                if (isset($translations[$this->status]['message'])) {
                    return $translations[$this->status]['message'];
                }
            }
        }

        // Ultimate fallback for unknown status codes
        return 'Unknown Status';
    }

    /**
     * Get translated details for the current status
     *
     * @return string
     */
    protected function getDetails(): string
    {
        if ($this->customDetails) {
            return $this->customDetails;
        }

        $language = $this->getLanguage();

        // Try to load translation from the language file
        $translationPath = __DIR__ . "/lang/{$language}/messages.php";

        if (file_exists($translationPath)) {
            $translations = include $translationPath;
            if (isset($translations[$this->status]['details'])) {
                return $translations[$this->status]['details'];
            }
        }

        // Fallback to English if current language file doesn't have the status
        if ($language !== 'en') {
            $englishPath = __DIR__ . "/lang/en/messages.php";
            if (file_exists($englishPath)) {
                $translations = include $englishPath;
                if (isset($translations[$this->status]['details'])) {
                    return $translations[$this->status]['details'];
                }
            }
        }

        // Ultimate fallback for unknown status codes
        return 'No additional details available';
    }

    /**
     * Get the response data
     *
     * @return mixed
     */
    protected function getData(): mixed
    {
        return $this->data ?? [];
    }

    /**
     * Build the response array
     *
     * @return array
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
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->buildResponse();
    }

    /**
     * Convert response to JSON string (logging/external systems)
     *
     * @return string
     */
    public function toJsonString(): string
    {
        return json_encode($this->buildResponse(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    /**
     * Convert response to Laravel Collection (fluent handling)
     *
     * @return Collection
     */
    public function toCollection(): Collection
    {
        return collect($this->buildResponse());
    }

    /**
     * Convert response to JsonResponse (API/Controller use)
     *
     * @param int $options JSON encoding options
     * @return JsonResponse
     */
    public function toJson(int $options = 0): JsonResponse
    {
        return response()->json($this->buildResponse(), $this->status, [], $options);
    }

    /**
     * Convert response to Response (Web routes)
     *
     * @return Response
     */
    public function toResponse(): Response
    {
        return response($this->toJsonString(), $this->status, [
            'Content-Type' => 'application/json'
        ]);
    }

    /**
     * Send response directly (direct output)
     *
     * @return void
     */
    public function send(): void
    {
        echo $this->toJsonString();
    }

    /**
     * Convert to string (debug/echo)
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJsonString();
    }
}
