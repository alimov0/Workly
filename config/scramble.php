<?php

use Dedoc\Scramble\Http\Middleware\RestrictedDocsAccess;

return [
    
    'api_path' => 'api',

    'api_domain' => null,

    'export_path' => 'api.json',

    'info' => [
        
        'version' => env('API_VERSION', '0.0.1'),

        'description' => '',
    ],

    'ui' => [

        'title' => null,


        'theme' => 'light',

        'hide_try_it' => false,

        'hide_schemas' => false,

        'logo' => '',

        'try_it_credentials_policy' => 'include',

        'layout' => 'responsive',
    ],

    /*
     * The list of servers of the API. By default, when `null`, server URL will be created from
     * `scramble.api_path` and `scramble.api_domain` config variables. When providing an array, you
     * will need to specify the local server URL manually (if needed).
     *
     * Example of non-default config (final URLs are generated using Laravel `url` helper):
     *
     * ```php
     * 'servers' => [
     *     'Live' => 'api',
     *     'Prod' => 'https://scramble.dedoc.co/api',
     * ],
     * ```
     */
    'servers' => null,

    /**
     * Determines how Scramble stores the descriptions of enum cases.
     * Available options:
     * - 'description' – Case descriptions are stored as the enum schema's description using table formatting.
     * - 'extension' – Case descriptions are stored in the `x-enumDescriptions` enum schema extension.
     *
     *    @see https://redocly.com/docs-legacy/api-reference-docs/specification-extensions/x-enum-descriptions
     * - false - Case descriptions are ignored.
     */
    'enum_cases_description_strategy' => 'description',

    'middleware' => [
        'web',
        RestrictedDocsAccess::class,
    ],

    'extensions' => [],
];
