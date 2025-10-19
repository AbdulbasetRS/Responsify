<?php

namespace Abdulbaset\Responsify\Tests;

use Abdulbaset\Responsify\Respond;
use Abdulbaset\Responsify\Enums\Language;
use PHPUnit\Framework\TestCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class RespondTest extends TestCase
{
    /** @test */
    public function it_can_create_response_with_status_only()
    {
        $response = Respond::status(200);

        $expected = [
            'status' => 200,
            'message' => 'OK',
            'details' => 'The request was successful.',
            'data' => []
        ];

        $this->assertEquals($expected, $response->toArray());
    }

    /** @test */
    public function it_can_chain_message_method()
    {
        $response = Respond::status(201)
            ->message('User created successfully');

        $expected = [
            'status' => 201,
            'message' => 'User created successfully',
            'details' => 'The resource was successfully created.',
            'data' => []
        ];

        $this->assertEquals($expected, $response->toArray());
    }

    /** @test */
    public function it_can_chain_details_method()
    {
        $response = Respond::status(400)
            ->details('Invalid input provided');

        $expected = [
            'status' => 400,
            'message' => 'Bad Request',
            'details' => 'Invalid input provided',
            'data' => []
        ];

        $this->assertEquals($expected, $response->toArray());
    }

    /** @test */
    public function it_can_chain_data_method()
    {
        $data = ['id' => 1, 'name' => 'John Doe'];
        $response = Respond::status(200)
            ->data($data);

        $expected = [
            'status' => 200,
            'message' => 'OK',
            'details' => 'The request was successful.',
            'data' => $data
        ];

        $this->assertEquals($expected, $response->toArray());
    }

    /** @test */
    public function it_can_use_enum_for_language_setting()
    {
        $response = Respond::status(200)
            ->language(Language::ARABIC->value);

        $result = $response->toArray();

        $this->assertEquals(200, $result['status']);
        // Should use Arabic translations (message should be different from English)
        $this->assertNotEquals('OK', $result['message']);
    }

    /** @test */
    public function it_can_chain_string_language_setting()
    {
        $response = Respond::status(200)
            ->language('ar');

        $result = $response->toArray();

        $this->assertEquals(200, $result['status']);
        // Should use Arabic translations
        $this->assertNotEquals('OK', $result['message']);
    }

    /** @test */
    public function it_ignores_invalid_language_codes()
    {
        $response = Respond::status(200)
            ->language('invalid_language_code');

        $result = $response->toArray();

        // Should still work but fall back to default language (English)
        $this->assertEquals(200, $result['status']);
        $this->assertEquals('OK', $result['message']);
    }

    /** @test */
    public function it_can_chain_all_methods()
    {
        $data = ['user' => ['id' => 1, 'name' => 'John']];
        $response = Respond::status(201)
            ->message('User created')
            ->details('Account created successfully')
            ->data($data)
            ->language('en');

        $expected = [
            'status' => 201,
            'message' => 'User created',
            'details' => 'Account created successfully',
            'data' => $data
        ];

        $this->assertEquals($expected, $response->toArray());
    }

    /** @test */
    public function it_supports_different_languages()
    {
        $supportedLanguages = ['en', 'ar', 'de', 'fr', 'es', 'it'];

        foreach ($supportedLanguages as $lang) {
            $response = Respond::status(200)->language($lang);

            // Should not throw an exception and should return valid response
            $this->assertIsArray($response->toArray());
            $this->assertEquals(200, $response->toArray()['status']);
        }
    }

    /** @test */
    public function it_returns_json_response()
    {
        $response = Respond::status(200)->toJson();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    public function it_returns_response_for_web_routes()
    {
        $response = Respond::status(200)->toResponse();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    public function it_returns_collection()
    {
        $response = Respond::status(200)->toCollection();

        $this->assertInstanceOf(Collection::class, $response);
        $this->assertEquals(200, $response->get('status'));
    }

    /** @test */
    public function it_returns_json_string()
    {
        $response = Respond::status(200)->toJsonString();

        $this->assertIsString($response);
        $this->assertJson($response);

        $decoded = json_decode($response, true);
        $this->assertEquals(200, $decoded['status']);
    }

    /** @test */
    public function it_can_be_converted_to_string()
    {
        $response = Respond::status(200);

        $this->assertIsString((string) $response);
        $this->assertJson((string) $response);
    }

    /** @test */
    public function it_can_send_response_directly()
    {
        $response = Respond::status(200);

        // Capture output
        ob_start();
        $response->send();
        $output = ob_get_clean();

        $this->assertJson($output);

        $decoded = json_decode($output, true);
        $this->assertEquals(200, $decoded['status']);
    }

    /** @test */
    public function it_handles_unknown_status_codes()
    {
        $response = Respond::status(999);

        $result = $response->toArray();

        $this->assertEquals(999, $result['status']);
        $this->assertEquals('Unknown Status', $result['message']);
        $this->assertEquals('No additional details available', $result['details']);
    }

    /** @test */
    public function it_falls_back_to_english_for_missing_translations()
    {
        $response = Respond::status(200)->language('fr'); // French might not have all translations

        $result = $response->toArray();

        $this->assertEquals(200, $result['status']);
        // Should either use French translation or fall back to English
        $this->assertNotEmpty($result['message']);
        $this->assertNotEmpty($result['details']);
    }

    /** @test */
    public function it_implements_required_interfaces()
    {
        $response = Respond::status(200);

        $this->assertInstanceOf(\Abdulbaset\Responsify\Contracts\ResponseBuilderInterface::class, $response);
        $this->assertInstanceOf(\Abdulbaset\Responsify\Contracts\ResponseFormatterInterface::class, $response);
    }

    /** @test */
    public function it_can_handle_null_data()
    {
        $response = Respond::status(200)->data(null);

        $result = $response->toArray();

        $this->assertEquals([], $result['data']);
    }

    /** @test */
    public function it_can_handle_empty_data()
    {
        $response = Respond::status(200)->data([]);

        $result = $response->toArray();

        $this->assertEquals([], $result['data']);
    }

    /** @test */
    public function it_can_handle_complex_data()
    {
        $complexData = [
            'users' => [
                ['id' => 1, 'name' => 'John'],
                ['id' => 2, 'name' => 'Jane']
            ],
            'meta' => [
                'total' => 2,
                'page' => 1
            ]
        ];

        $response = Respond::status(200)->data($complexData);

        $result = $response->toArray();

        $this->assertEquals($complexData, $result['data']);
    }

    /** @test */
    public function it_can_handle_different_http_status_codes()
    {
        $statusCodes = [100, 200, 201, 204, 301, 400, 401, 403, 404, 422, 500];

        foreach ($statusCodes as $status) {
            $response = Respond::status($status);

            $result = $response->toArray();

            $this->assertEquals($status, $result['status']);
            $this->assertNotEmpty($result['message']);
            $this->assertNotEmpty($result['details']);
        }
    }

    /** @test */
    public function it_can_handle_json_encoding_options()
    {
        $data = ['special' => 'chars: àáâãäå'];

        $response = Respond::status(200)->data($data);

        // Should handle Unicode characters properly
        $jsonString = $response->toJsonString();
        $this->assertJson($jsonString);

        $decoded = json_decode($jsonString, true);
        $this->assertEquals($data, $decoded['data']);
    }

    /** @test */
    public function it_maintains_fluent_interface()
    {
        // Test that all methods return the same instance for chaining
        $response = Respond::status(200);

        $this->assertSame($response, $response->message('test'));
        $this->assertSame($response, $response->details('test'));
        $this->assertSame($response, $response->data([]));
        $this->assertSame($response, $response->language('en'));
    }

    /** @test */
    public function it_can_be_used_statically()
    {
        // Test static usage
        $this->assertInstanceOf(Respond::class, Respond::status(200));
    }

    /** @test */
    public function it_has_proper_constructor_protection()
    {
        // Test that we can't instantiate directly (should use static method)
        $reflection = new \ReflectionClass(Respond::class);
        $constructor = $reflection->getConstructor();

        $this->assertTrue($constructor->isPrivate() || $constructor->isProtected());
    }
}
