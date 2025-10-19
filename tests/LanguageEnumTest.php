<?php

namespace Abdulbaset\Responsify\Tests;

use Abdulbaset\Responsify\Enums\Language;
use PHPUnit\Framework\TestCase;

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
        $this->assertTrue(Language::isSupported('de'));
        $this->assertTrue(Language::isSupported('fr'));
        $this->assertTrue(Language::isSupported('es'));
        $this->assertTrue(Language::isSupported('it'));

        $this->assertFalse(Language::isSupported('invalid'));
        $this->assertFalse(Language::isSupported(''));
        $this->assertFalse(Language::isSupported('xyz'));
    }

    /** @test */
    public function it_can_get_language_from_code()
    {
        $this->assertEquals(Language::ENGLISH, Language::fromCode('en'));
        $this->assertEquals(Language::ARABIC, Language::fromCode('ar'));
        $this->assertEquals(Language::GERMAN, Language::fromCode('de'));
        $this->assertEquals(Language::FRENCH, Language::fromCode('fr'));
        $this->assertEquals(Language::SPANISH, Language::fromCode('es'));
        $this->assertEquals(Language::ITALIAN, Language::fromCode('it'));

        $this->assertNull(Language::fromCode('invalid'));
        $this->assertNull(Language::fromCode(''));
    }

    /** @test */
    public function it_has_display_names()
    {
        $this->assertEquals('English', Language::ENGLISH->getDisplayName());
        $this->assertEquals('العربية', Language::ARABIC->getDisplayName());
        $this->assertEquals('Deutsch', Language::GERMAN->getDisplayName());
        $this->assertEquals('Français', Language::FRENCH->getDisplayName());
        $this->assertEquals('Español', Language::SPANISH->getDisplayName());
        $this->assertEquals('Italiano', Language::ITALIAN->getDisplayName());
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

    /** @test */
    public function it_can_get_all_codes_statically()
    {
        $codes = Language::getAllCodes();

        $this->assertIsArray($codes);
        $this->assertCount(6, $codes);
        $this->assertContains('en', $codes);
        $this->assertContains('ar', $codes);
        $this->assertContains('de', $codes);
        $this->assertContains('fr', $codes);
        $this->assertContains('es', $codes);
        $this->assertContains('it', $codes);
    }

    /** @test */
    public function it_handles_case_sensitive_codes()
    {
        // Test that codes are case-sensitive
        $this->assertTrue(Language::isSupported('en'));
        $this->assertFalse(Language::isSupported('EN'));
        $this->assertFalse(Language::isSupported('En'));
    }

    /** @test */
    public function it_can_be_used_in_arrays_and_collections()
    {
        $languages = [
            Language::ENGLISH,
            Language::ARABIC,
            Language::GERMAN
        ];

        $this->assertCount(3, $languages);

        foreach ($languages as $language) {
            $this->assertInstanceOf(Language::class, $language);
            $this->assertIsString($language->value);
            $this->assertNotEmpty($language->getDisplayName());
        }
    }

    /** @test */
    public function it_can_be_serialized()
    {
        $language = Language::ARABIC;

        // Test serialization
        $serialized = serialize($language);
        $unserialized = unserialize($serialized);

        $this->assertEquals($language, $unserialized);
        $this->assertEquals('ar', $unserialized->value);
    }

    /** @test */
    public function it_can_be_used_in_switch_statements()
    {
        $testLanguage = Language::FRENCH;

        $result = match($testLanguage) {
            Language::ENGLISH => 'English selected',
            Language::ARABIC => 'Arabic selected',
            Language::FRENCH => 'French selected',
            Language::GERMAN => 'German selected',
            Language::SPANISH => 'Spanish selected',
            Language::ITALIAN => 'Italian selected',
        };

        $this->assertEquals('French selected', $result);
    }

    /** @test */
    public function it_can_be_compared()
    {
        $lang1 = Language::ENGLISH;
        $lang2 = Language::fromCode('en');
        $lang3 = Language::ARABIC;

        $this->assertTrue($lang1 === $lang2);
        $this->assertFalse($lang1 === $lang3);
        $this->assertTrue($lang1 == $lang2); // Loose comparison should also work
    }

    /** @test */
    public function it_provides_useful_debugging_info()
    {
        $language = Language::ARABIC;

        // Should provide useful string representation
        $this->assertIsString((string) $language);
        $this->assertEquals('ar', (string) $language);

        // Should provide useful var_dump info
        ob_start();
        var_dump($language);
        $output = ob_get_clean();

        $this->assertStringContains('enum(Language::ARABIC)', $output);
    }
}
