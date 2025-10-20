<?php

// Config/responsify.php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Language
    |--------------------------------------------------------------------------
    |
    | This option defines the default language used for response messages.
    | 
    | Supported languages:
    |   - 'ar': Arabic
    |   - 'en': English
    |   - 'de': German
    |   - 'fr': French
    |   - 'es': Spanish
    |   - 'it': Italian
    |
    | -------------------------------------------------------------------------
    | 🔹 Language Priority (from highest to lowest)
    | -------------------------------------------------------------------------
    |
    | 1. Manually set language via ->language() method.
    | 2. Application locale from config('app.locale'), 
    |    but only if it’s supported by the package.
    | 3. This config value: config('responsify.language'), 
    |    if defined and supported.
    | 4. Fallback to English ('en') if none of the above are valid.
    |
    | -------------------------------------------------------------------------
    | 🔸 Notes
    | -------------------------------------------------------------------------
    | - If this value is set to null, the package will try to use the
    |   application's default locale (config('app.locale')).
    | - If the app locale is not supported by the package, it will use
    |   the fallback language ('en').
    |
    */
    'language' => 'en',
];