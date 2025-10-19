<?php

use PHPUnit\Framework\TestCase;
use Abdulbaset\Responsify\Enums\Language;

class LanguageEnumTest extends TestCase
{
    /** @test */
    public function it_has_all_required_language_cases()
    {
        $expectedCases = ['en', 'ar', 'de', 'fr', 'es', 'it'];

        $actualCodes = Language::getAllCodes();

        $this->assertEquals($expectedCases, $actualCodes);
    }

    /** @test */
    public function it_can_check_if_language_is_supported()
    {
        $this->assertTrue(Language::isSupported('en'));
        $this->assertTrue(Language::isSupported('ar'));
        $this->assertFalse(Language::isSupported('invalid'));
    }

    /** @test */
    public function it_can_get_language_from_code()
    {
        $this->assertEquals(Language::ENGLISH, Language::fromCode('en'));
        $this->assertEquals(Language::ARABIC, Language::fromCode('ar'));
        $this->assertNull(Language::fromCode('invalid'));
    }

    /** @test */
    public function it_has_display_names()
    {
        $this->assertEquals('English', Language::ENGLISH->getDisplayName());
        $this->assertEquals('العربية', Language::ARABIC->getDisplayName());
        $this->assertEquals('Deutsch', Language::GERMAN->getDisplayName());
    }

    /** @test */
    public function it_has_correct_language_values()
    {
        $this->assertEquals('en', Language::ENGLISH->value);
        $this->assertEquals('ar', Language::ARABIC->value);
        $this->assertEquals('de', Language::GERMAN->value);
        $this->assertEquals('fr', Language::FRENCH->value);
        $this->assertEquals('es', Language::SPANISH->value);
        $this->assertEquals('it', Language::ITALIAN->value);
    }
}
