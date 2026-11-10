<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default System Timezone
    |--------------------------------------------------------------------------
    |
    | The default timezone used as the system/database timezone. This is the
    | timezone that all dates are stored in. Falls back to app.timezone.
    |
    */

    'system_timezone' => config('app.timezone', 'UTC'),

    /*
    |--------------------------------------------------------------------------
    | Fallback User Timezone
    |--------------------------------------------------------------------------
    |
    | When the user's timezone cannot be detected (e.g. first request before
    | JavaScript runs), this timezone will be used as the default.
    |
    */

    'fallback_timezone' => config('app.timezone', 'UTC'),

    /*
    |--------------------------------------------------------------------------
    | Header Name
    |--------------------------------------------------------------------------
    |
    | The HTTP header name used to send the timezone from the browser.
    |
    */

    'header_name' => env('TIMEZONE_DETECTOR_HEADER', 'X-Timezone'),

    /*
    |--------------------------------------------------------------------------
    | Cookie Name
    |--------------------------------------------------------------------------
    |
    | The cookie name used as a fallback to store the user's timezone.
    |
    */

    'cookie_name' => env('TIMEZONE_DETECTOR_COOKIE', 'user_timezone'),

    /*
    |--------------------------------------------------------------------------
    | Session Key
    |--------------------------------------------------------------------------
    |
    | The session key used to store the detected user timezone.
    |
    */

    'session_key' => env('TIMEZONE_DETECTOR_SESSION_KEY', 'user_timezone'),

    /*
    |--------------------------------------------------------------------------
    | Query Parameter Name
    |--------------------------------------------------------------------------
    |
    | The query/post parameter name used to detect timezone from requests.
    |
    */

    'query_param' => env('TIMEZONE_DETECTOR_QUERY_PARAM', 'timezone'),

    /*
    |--------------------------------------------------------------------------
    | Auto-register Middleware
    |--------------------------------------------------------------------------
    |
    | When true, the DetectUserTimezone middleware will be automatically
    | registered for the Filament panel using this plugin. Set to false
    | if you prefer to register it manually.
    |
    */

    'auto_middleware' => env('TIMEZONE_DETECTOR_AUTO_MIDDLEWARE', true),

    /*
    |--------------------------------------------------------------------------
    | Register Carbon Macros
    |--------------------------------------------------------------------------
    |
    | When true, Carbon macros (toUserTimezone, toSystemTimezone) will be
    | registered automatically, allowing you to call them on any Carbon
    | instance: $date->toUserTimezone()
    |
    */

    'register_carbon_macros' => env('TIMEZONE_DETECTOR_CARBON_MACROS', true),

    /*
    |--------------------------------------------------------------------------
    | Register Column Macros
    |--------------------------------------------------------------------------
    |
    | When true, a toUserTimezone() macro will be added to Filament's
    | TextColumn and other table columns, enabling easy timezone conversion
    | in tables: TextColumn::make('created_at')->dateTime()->toUserTimezone()
    |
    */

    'register_column_macros' => env('TIMEZONE_DETECTOR_COLUMN_MACROS', true),

    /*
    |--------------------------------------------------------------------------
    | Register Form Field Macros
    |--------------------------------------------------------------------------
    |
    | When true, toUserTimezone() and fromUserTimezone() macros will be added
    | to Filament's DateTimePicker, enabling easy timezone conversion in forms.
    |
    */

    'register_field_macros' => env('TIMEZONE_DETECTOR_FIELD_MACROS', true),

];
