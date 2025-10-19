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
// Set specific language
return Respond::status(200)
    ->language('ar') // Arabic
    ->toJson();

return Respond::status(404)
    ->language('fr') // French
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

Run the tests with:

```bash
composer test
```

Or run PHPUnit directly:

```bash
vendor/bin/phpunit
```

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
