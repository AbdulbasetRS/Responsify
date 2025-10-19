<?php

namespace Abdulbaset\Responsify\Tests;

use Abdulbaset\Responsify\Respond;
use Abdulbaset\Responsify\Enums\Language;
use PHPUnit\Framework\TestCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class RespondIntegrationTest extends TestCase
{
    /** @test */
    public function it_can_use_enum_directly_in_language_method()
    {
        // Test using enum value directly
        $response = Respond::status(200)
            ->language(Language::ARABIC->value);

        $result = $response->toArray();

        $this->assertEquals(200, $result['status']);
        $this->assertNotEquals('OK', $result['message']); // Should be Arabic
    }

    /** @test */
    public function it_can_handle_all_enum_languages_with_respond()
    {
        foreach (Language::cases() as $language) {
            $response = Respond::status(200)->language($language->value);

            $result = $response->toArray();

            $this->assertEquals(200, $result['status']);
            $this->assertNotEmpty($result['message']);
            $this->assertNotEmpty($result['details']);
        }
    }

    /** @test */
    public function it_can_convert_enum_to_collection_with_all_data()
    {
        $response = Respond::status(200)
            ->data(['test' => 'data'])
            ->language(Language::FRENCH->value);

        $collection = $response->toCollection();

        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertEquals(['test' => 'data'], $collection->get('data'));

        // Test that we can manipulate the collection
        $filtered = $collection->only(['status', 'message']);
        $this->assertArrayHasKey('status', $filtered);
        $this->assertArrayHasKey('message', $filtered);
    }

    /** @test */
    public function it_can_handle_large_data_structures()
    {
        $largeData = [
            'users' => array_fill(0, 1000, ['id' => 1, 'name' => 'Test User']),
            'metadata' => [
                'total' => 1000,
                'page' => 1,
                'per_page' => 1000
            ]
        ];

        $response = Respond::status(200)->data($largeData);

        $result = $response->toArray();
        $this->assertEquals($largeData, $result['data']);

        // Test that JSON serialization works with large data
        $jsonString = $response->toJsonString();
        $this->assertJson($jsonString);

        $decoded = json_decode($jsonString, true);
        $this->assertEquals($largeData, $decoded['data']);
    }

    /** @test */
    public function it_can_handle_special_characters_in_messages()
    {
        $specialMessage = 'Message with spécial çháráctérs: àáâãäå';
        $specialDetails = 'Details with émôjis 🚀 and ñúméros: 123';

        $response = Respond::status(200)
            ->message($specialMessage)
            ->details($specialDetails);

        $result = $response->toArray();

        $this->assertEquals($specialMessage, $result['message']);
        $this->assertEquals($specialDetails, $result['details']);

        // Test JSON encoding with special characters
        $jsonString = $response->toJsonString();
        $this->assertJson($jsonString);

        $decoded = json_decode($jsonString, true);
        $this->assertEquals($specialMessage, $decoded['message']);
        $this->assertEquals($specialDetails, $decoded['details']);
    }

    /** @test */
    public function it_can_handle_mixed_data_types()
    {
        $mixedData = [
            'string' => 'test',
            'integer' => 42,
            'float' => 3.14,
            'boolean' => true,
            'null_value' => null,
            'array' => [1, 2, 3],
            'object' => (object)['key' => 'value'],
            'nested' => [
                'deep' => [
                    'value' => 'found'
                ]
            ]
        ];

        $response = Respond::status(200)->data($mixedData);

        $result = $response->toArray();
        $this->assertEquals($mixedData, $result['data']);

        // Test JSON serialization
        $jsonString = $response->toJsonString();
        $this->assertJson($jsonString);

        $decoded = json_decode($jsonString, true);
        $this->assertEquals($mixedData, $decoded['data']);
    }

    /** @test */
    public function it_can_handle_empty_and_null_responses()
    {
        // Test with empty data
        $response1 = Respond::status(204);
        $this->assertEquals([], $response1->toArray()['data']);

        // Test with null data
        $response2 = Respond::status(200)->data(null);
        $this->assertEquals([], $response2->toArray()['data']);

        // Test with empty array data
        $response3 = Respond::status(200)->data([]);
        $this->assertEquals([], $response3->toArray()['data']);
    }

    /** @test */
    public function it_can_be_used_in_different_output_formats()
    {
        $response = Respond::status(201)
            ->message('Created successfully')
            ->data(['id' => 123]);

        // Test all output formats
        $this->assertIsArray($response->toArray());
        $this->assertInstanceOf(Collection::class, $response->toCollection());
        $this->assertIsString($response->toJsonString());
        $this->assertInstanceOf(JsonResponse::class, $response->toJson());
        $this->assertInstanceOf(Response::class, $response->toResponse());

        // Test that all formats contain the same data
        $arrayData = $response->toArray();
        $collectionData = $response->toCollection()->toArray();
        $jsonData = json_decode($response->toJsonString(), true);

        $this->assertEquals($arrayData, $collectionData);
        $this->assertEquals($arrayData, $jsonData);
    }

    /** @test */
    public function it_can_handle_language_switching_mid_chain()
    {
        $response = Respond::status(200)
            ->language('en')
            ->message('English message')
            ->language('ar')
            ->details('Arabic details');

        $result = $response->toArray();

        // Should use the last language set (Arabic)
        $this->assertNotEquals('English message', $result['message']);
        $this->assertEquals('Arabic details', $result['details']);
    }

    /** @test */
    public function it_can_be_used_with_helper_function()
    {
        // Test the global helper function
        $response = respond(200)->message('Helper test');

        $this->assertInstanceOf(Respond::class, $response);
        $this->assertEquals(200, $response->toArray()['status']);
        $this->assertEquals('Helper test', $response->toArray()['message']);
    }
}
