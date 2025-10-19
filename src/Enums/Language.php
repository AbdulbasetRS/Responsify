<?php

namespace Abdulbaset\Responsify\Enums;

/**
 * Supported languages for Responsify responses
 */
enum Language: string
{
    case ENGLISH = 'en';
    case ARABIC = 'ar';
    case GERMAN = 'de';
    case FRENCH = 'fr';
    case SPANISH = 'es';
    case ITALIAN = 'it';

    /**
     * Get the display name for the language
     */
    public function getDisplayName(): string
    {
        return match($this) {
            self::ENGLISH => 'English',
            self::ARABIC => 'العربية',
            self::GERMAN => 'Deutsch',
            self::FRENCH => 'Français',
            self::SPANISH => 'Español',
            self::ITALIAN => 'Italiano',
        };
    }

    /**
     * Get all supported language codes
     */
    public static function getAllCodes(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Check if a language code is supported
     */
    public static function isSupported(string $code): bool
    {
        return in_array($code, self::getAllCodes());
    }

    /**
     * Get language from code
     */
    public static function fromCode(string $code): ?self
    {
        foreach (self::cases() as $case) {
            if ($case->value === $code) {
                return $case;
            }
        }
        return null;
    }
}
