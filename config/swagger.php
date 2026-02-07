<?php

return [
    'default' => 'default',
    'documentations' => [
        'default' => [
            'api' => [
                'title' => 'EventLite API Documentation',
            ],

            'routes' => [
                /*
                 * Route for accessing api documentation interface
                 */
                'api' => 'api/documentation',
            ],
            'paths' => [
                'use_absolute_path' => true,
                'swagger_ui_assets_path' => 'vendor/swagger-api/swagger-ui/dist/',
                'docs_json' => 'api-docs.json',
                'docs_yaml' => 'api-docs.yaml',
                'format_to_use_for_docs' => 'json',

                /*
                 * Absolute paths to directory containing the swagger annotations are stored.
                 */
                'annotations' => [
                    base_path('app/Http/Controllers'),
                ],
            ],
        ],
    ],
    'defaults' => [
        'routes' => [
            /*
             * Route for accessing parsed swagger annotations.
             */
            'docs' => 'docs',

            /*
             * Route for Oauth2 authentication callback.
             */
            'oauth2_callback' => 'api/oauth2-callback',

            /*
             * Middleware allows to prevent unexpected access to API documentation
             */
            'middleware' => [
                'api' => [],
                'asset' => [],
                'docs' => [],
                'oauth2_callback' => [],
            ],

            /*
             * Route Group options
             */
            'group_options' => [],
        ],

        'paths' => [
            /*
             * Absolute path to location where parsed annotations will be stored
             */
            'docs' => storage_path('api-docs'),

            'views' => base_path('resources/views/vendor/swagger'),
            'base' => null,

            /*
             * Absolute path to directories that should be excluded from scanning
             * @deprecated Please use `scanOptions.exclude`
             * `scanOptions.exclude` overwrites this
             */
            'excludes' => [],
        ],

        'scanOptions' => [
            /**
             * Configuration for default processors. Allows to pass processors configuration to swagger-php.
             *
             * @link https://zircote.github.io/swagger-php/reference/processors.html
             */
            'default_processors_configuration' => [
            /** Example */
            /**
             * 'operationId.hash' => true,
             * 'pathFilter' => [
             * 'tags' => [
             * '/pets/',
             * '/store/',
             * ],
             * ],.
             */
            ],

            /**
             * analyser: defaults to \OpenApi\StaticAnalyser .
             *
             * @see \OpenApi\scan
             */
            'analyser' => null,

            /**
             * analysis: defaults to a new \OpenApi\Analysis .
             *
             * @see \OpenApi\scan
             */
            'analysis' => null,

            /**
             * Custom query path processors classes.
             *
             * @link https://github.com/zircote/swagger-php/tree/master/Examples/processors/schema-query-parameter
             * @see \OpenApi\scan
             */
            'processors' => [
                // new \App\SwaggerProcessors\SchemaQueryParameter(),
            ],

            /**
             * pattern: string       $pattern File pattern(s) to scan (default: *.php) .
             *
             * @see \OpenApi\scan
             */
            'pattern' => null,

            /*
             * Absolute path to directories that should be excluded from scanning
             * @note This option overwrites `paths.excludes`
             * @see \OpenApi\scan
             */
            'exclude' => [],

            'open_api_spec_version' => \L5Swagger\Generator::OPEN_API_DEFAULT_SPEC_VERSION,
        ],

        /*
         * API security definitions. Will be generated into documentation file.
        */
        'securityDefinitions' => [
            'securitySchemes' => [
                'bearerAuth' => [
                    'type' => 'http',
                    'scheme' => 'bearer',
                    'bearerFormat' => 'JWT',
                    'description' => 'Enter JWT token',
                ],
            ],
            'security' => [
                [
                    'bearerAuth' => [],
                ],
            ],
        ],

        'generate_always' => env('SWAGGER_GENERATE_ALWAYS', true),
        'generate_yaml_copy' => false,

        /*
         * Edit to trust the proxy's ip address - needed for AWS Load Balancer
         * string[]
         */
        'proxy' => false,

        /*
         * Configs plugin allows to fetch external configs instead of passing them to SwaggerUIBundle.
         * See more at: https://github.com/swagger-api/swagger-ui#configs-plugin
         */
        'additional_config_url' => null,

        'operations_sort' => null,

        /*
         * Pass the validatorUrl parameter to SwaggerUi init on the JS side.
         * A null value here disables validation.
         */
        'validator_url' => null,

        /*
         * Swagger UI configuration parameters
         */
        'ui' => [
            'display' => [
                'dark_mode' => false,
                'doc_expansion' => 'none',
                'filter' => true,
            ],
            'authorization' => [
                'persist_authorization' => false,
            ],
        ],
        'constants' => [
        ],
    ],
];
