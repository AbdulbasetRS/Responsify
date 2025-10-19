# Responsify

[![Latest Version on Packagist](https://img.shields.io/packagist/v/abdulbaset/responsify.svg?style=flat-square)](https://packagist.org/packages/abdulbaset/responsify)
[![PHP Version Require](https://img.shields.io/packagist/php-v/abdulbaset/responsify)](https://packagist.org/packages/abdulbaset/responsify)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

A powerful Laravel package for creating standardized API responses with multi-language support and fluent interface.

## 📦 Installation

You can install the package via composer:

```bash
composer require abdulbaset/responsify
```

### Laravel Integration

Publish the configuration and language files:

```bash
php artisan vendor:publish --provider="Abdulbaset\Responsify\Providers\ResponsifyServiceProvider"
```

This will publish:
- `config/responsify.php` - Configuration file for default language settings
- `lang/vendor/responsify/` - Translation files for multiple languages

### 🧪 Testing Setup

For development and testing, install PHPUnit and run the comprehensive test suite:

```bash
# Install PHPUnit for testing
composer install

# Run all tests
composer test

# Or run PHPUnit directly
vendor/bin/phpunit

# Run with detailed output
vendor/bin/phpunit --testdox

# Generate HTML coverage report
vendor/bin/phpunit --coverage-html coverage
```

The package includes **60+ test cases** covering:
- ✅ Core functionality and method chaining
- ✅ Language enum integration and validation
- ✅ Error handling and edge cases
- ✅ Integration between all components
- ✅ Unicode and special character support

## 🚀 Usage

### Basic Usage

```php
use Abdulbaset\Responsify\Respond;

// Simple response with status only
return Respond::status(200)->toJson();

// Response with custom message
return Respond::status(201)
    ->message('User created successfully')
    ->toJson();

// Response with all options
return Respond::status(201)
    ->message('User created successfully')
    ->details('User account has been created and stored in database')
    ->data($user)
    ->toJson();
```

### Helper Function

You can also use the global helper function for shorter syntax:

```php
use Abdulbaset\Responsify\Respond;

// Using helper function
return respond(200)->toJson();

return respond(404)
    ->message('Resource not found')
    ->toJson();
```

### Language Support

The package supports multiple languages out of the box:

```php
// Set specific language using string
return Respond::status(200)
    ->language('ar') // Arabic
    ->toJson();

return Respond::status(404)
    ->language('fr') // French
    ->toJson();

// Or use the Language enum for better type safety
use Abdulbaset\Responsify\Enums\Language;

return Respond::status(200)
    ->language(Language::ARABIC->value) // Arabic
    ->toJson();

// Supported languages: en, ar, de, fr, es, it
```

### Automatic Language Fallback

The package automatically handles language fallbacks:

1. **Manual language** (if set via `language()` method)
2. **Config default** (`config('responsify.language')`)
3. **App locale** (`config('app.locale')`)
4. **English** (ultimate fallback)

### Output Formats

#### JSON Response (API/Controllers)
```php
return Respond::status(200)
    ->data($users)
    ->toJson();
```

#### Array (Internal/Testing)
```php
$response = Respond::status(200)->toArray();
// Returns: ['status' => 200, 'message' => 'OK', 'details' => '...', 'data' => []]
```

#### JSON String (Logging/External Systems)
```php
$jsonString = Respond::status(200)->toJsonString();
// Returns: '{"status":200,"message":"OK","details":"...","data":[]}'
```

#### Laravel Collection (Fluent Handling)
```php
$collection = Respond::status(200)->toCollection();
// Returns: Illuminate\Support\Collection instance
```

#### HTTP Response (Web Routes)
```php
return Respond::status(200)->toResponse();
```

#### Direct Output
```php
Respond::status(200)->send(); // Outputs JSON directly
```

#### String Conversion (Debug/Echo)
```php
echo Respond::status(200); // Outputs JSON string
```

## 📋 Response Structure

All responses follow a consistent structure:

```json
{
    "status": 200,
    "message": "Operation completed successfully",
    "details": "Additional information about the operation",
    "data": {
        // Your data here
    }
}
```

## ⚙️ Configuration

The configuration file is located at `config/responsify.php`:

```php
<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Language
    |--------------------------------------------------------------------------
    |
    | This value determines the default language for response messages.
    | You can set it to one of the supported language codes:
    |
    | Supported languages:
    |   - 'ar': Arabic
    |   - 'en': English
    |   - 'de': German
    |   - 'fr': French
    |   - 'es': Spanish
    |   - 'it': Italian
    |
    */
    'language' => 'en',
];
```

## 🌍 Supported Languages

The package supports the following languages with full enum support:

```php
use Abdulbaset\Responsify\Enums\Language;

// Using enum constants
Language::ENGLISH    // 'en' - English
Language::ARABIC     // 'ar' - العربية (Arabic)
Language::GERMAN     // 'de' - Deutsch (German)
Language::FRENCH     // 'fr' - Français (French)
Language::SPANISH    // 'es' - Español (Spanish)
Language::ITALIAN    // 'it' - Italiano (Italian)

// Get all supported language codes
$codes = Language::getAllCodes(); // ['en', 'ar', 'de', 'fr', 'es', 'it']

// Check if language is supported
$isSupported = Language::isSupported('ar'); // true

// Get language from code
$language = Language::fromCode('ar'); // Language::ARABIC

// Get display name
$name = Language::ARABIC->getDisplayName(); // 'العربية'
```

### Language Codes:
- **English** (`en`) - Default
- **Arabic** (`ar`) - العربية
- **German** (`de`) - Deutsch
- **French** (`fr`) - Français
- **Spanish** (`es`) - Español
- **Italian** (`it`) - Italiano

## 🔧 Advanced Usage

### Custom Status Messages

The package includes comprehensive HTTP status code translations. If you need custom messages for specific status codes, you can override them:

```php
return Respond::status(418) // I'm a teapot
    ->message('Custom teapot message')
    ->toJson();
```

### Error Handling

```php
try {
    // Some operation
} catch (Exception $e) {
    return Respond::status(500)
        ->message('Internal server error')
        ->details('An unexpected error occurred')
        ->toJson();
}
```

### API Versioning

```php
return Respond::status(200)
    ->message('API v2 response')
    ->data($data)
    ->toJson();
```

## 🧪 Testing

The package includes comprehensive test suites that ensure reliability and catch regressions early. The tests cover all public methods, edge cases, error handling, and integration scenarios.

### 📊 Test Coverage

The test suite includes **60+ test cases** across 4 test files:

| Test File | Purpose | Test Count |
|-----------|---------|------------|
| **`RespondTest.php`** | Core Respond class functionality | 25+ tests |
| **`LanguageEnumTest.php`** | Language enum functionality | 15+ tests |
| **`RespondIntegrationTest.php`** | Integration between components | 12+ tests |
| **`RespondErrorHandlingTest.php`** | Error handling and edge cases | 10+ tests |

### 🚀 Running Tests

#### Basic Test Execution

```bash
# Run all tests
composer test

# Or run PHPUnit directly
vendor/bin/phpunit

# Run with detailed output
vendor/bin/phpunit --testdox

# Generate HTML coverage report
vendor/bin/phpunit --coverage-html coverage
```

#### Running Specific Test Suites

```bash
# Run only Respond class tests
vendor/bin/phpunit --filter=RespondTest

# Run only Language enum tests
vendor/bin/phpunit --filter=LanguageEnumTest

# Run only integration tests
vendor/bin/phpunit --filter=RespondIntegrationTest

# Run only error handling tests
vendor/bin/phpunit --filter=RespondErrorHandlingTest
```

#### Running Individual Tests

```bash
# Run specific test method
vendor/bin/phpunit --filter=test_can_create_response_with_status_only

# Run tests matching pattern
vendor/bin/phpunit --filter=test_can_chain

# Run tests for specific functionality
vendor/bin/phpunit --filter=test_language_enum
```

### 🔍 Test Categories

#### **Core Functionality Tests**
- ✅ Basic response creation with status codes
- ✅ Method chaining (message, details, data, language)
- ✅ All output formats (toJson, toArray, toJsonString, toCollection, toResponse)
- ✅ Language enum integration
- ✅ Helper function usage

#### **Language & Internationalization Tests**
- ✅ All supported languages (en, ar, de, fr, es, it)
- ✅ Language enum functionality and validation
- ✅ Fallback mechanisms for missing translations
- ✅ Unicode and special character handling

#### **Integration Tests**
- ✅ Complex data structures and large datasets
- ✅ Mixed data types (strings, numbers, objects, arrays)
- ✅ Concurrent usage and state isolation
- ✅ Helper function integration

#### **Error Handling & Edge Cases**
- ✅ Graceful handling of file system errors
- ✅ Memory limit and encoding issue handling
- ✅ Invalid input validation and recovery
- ✅ Configuration and environment error handling

### 📋 Test Examples

#### Testing Basic Usage
```bash
# Test basic response creation
vendor/bin/phpunit --filter=test_can_create_response_with_status_only

# Test method chaining
vendor/bin/phpunit --filter=test_can_chain_all_methods

# Test language switching
vendor/bin/phpunit --filter=test_can_use_enum_for_language_setting
```

#### Testing Language Features
```bash
# Test all language enum functionality
vendor/bin/phpunit --filter=LanguageEnumTest

# Test language validation
vendor/bin/phpunit --filter=test_can_check_if_language_is_supported

# Test language display names
vendor/bin/phpunit --filter=test_has_display_names
```

#### Testing Error Scenarios
```bash
# Test error handling
vendor/bin/phpunit --filter=RespondErrorHandlingTest

# Test graceful degradation
vendor/bin/phpunit --filter=test_handles_exceptions_gracefully

# Test Unicode handling
vendor/bin/phpunit --filter=test_can_handle_unicode_and_encoding_issues
```

### 🛠️ Development Workflow

#### Before Committing Code
```bash
# Run full test suite
composer test

# Run with coverage to ensure good test coverage
vendor/bin/phpunit --coverage-html coverage
```

#### Testing Specific Changes
```bash
# Test only the functionality you modified
vendor/bin/phpunit --filter=test_can_chain_message_method

# Run integration tests after API changes
vendor/bin/phpunit --filter=RespondIntegrationTest
```

#### Continuous Integration
```bash
# In your CI pipeline
composer install --no-dev
composer test --coverage-clover=coverage.xml
```

### 📈 Coverage Goals

The test suite aims for:
- **Function Coverage**: 100% of public methods
- **Line Coverage**: 90%+ of executable lines
- **Branch Coverage**: 85%+ of conditional branches
- **Error Path Coverage**: All major error scenarios

### 🐛 Debugging Failed Tests

If tests fail, you can:

```bash
# Run with verbose output to see details
vendor/bin/phpunit --verbose

# Run with debug information
vendor/bin/phpunit --debug

# Stop on first failure for quick debugging
vendor/bin/phpunit --stop-on-failure

# Generate coverage report to identify untested code
vendor/bin/phpunit --coverage-html coverage
```

### 🔧 Test Configuration

The PHPUnit configuration is in `phpunit.xml` and includes:
- Test suite discovery
- Code coverage filtering
- Environment setup for testing
- Bootstrap file configuration

### 📝 Adding New Tests

When adding new functionality:

1. **Add tests in the appropriate test file**
2. **Follow the existing naming convention**: `test_can_*` or `test_*`
3. **Test both success and failure scenarios**
4. **Include edge cases and boundary conditions**
5. **Run tests to ensure they pass**

```php
/** @test */
public function test_new_functionality()
{
    // Arrange
    $response = Respond::status(200);

    // Act
    $result = $response->newMethod();

    // Assert
    $this->assertEquals('expected_result', $result);
}
```

### 🎯 Best Practices

- **Write tests first** (TDD approach)
- **Keep tests focused** and isolated
- **Use descriptive test names** that explain what they test
- **Test behavior, not implementation**
- **Run tests frequently** during development
- **Maintain high test coverage** for reliability

## 📝 License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

## 👥 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## 📧 Support

For support, email abdulbasetredasayedhf@gmail.com or create an issue on GitHub.

## 🔗 Links

- [Packagist](https://packagist.org/packages/abdulbaset/responsify)
- [GitHub](https://github.com/AbdulbasetRS/Responsify)
- [LinkedIn](https://www.linkedin.com/in/abdulbaset-r-sayed/)
