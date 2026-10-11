<?php

use function Cake\Core\env;

/*
 * Local configuration file to provide any overrides to your app.php configuration.
 * Copy and save this file as app_local.php and make changes as required.
 * Note: It is not recommended to commit files with credentials such as app_local.php
 * into source code version control.
 */
return [
    /*
     * Debug Level:
     *
     * Production Mode:
     * false: No error messages, errors, or warnings shown.
     *
     * Development Mode:
     * true: Errors and warnings shown.
     */
    'debug' => filter_var(env('DEBUG', true), FILTER_VALIDATE_BOOLEAN),

    /*
     * Security and encryption configuration
     *
     * - salt - A random string used in security hashing methods.
     *   The salt value is also used as the encryption key.
     *   You should treat it as extremely sensitive data.
     */
    'Security' => [
        'salt' => env('SECURITY_SALT', '__SALT__'),
        'cake2Salt' => env('CAKE2_SECURITY_SALT', ''),
    ],

    /*
     * Connection information used by the ORM to connect
     * to your application's datastores.
     *
     * See app.php for more configuration options.
     */
    'Datasources' => [
        'default' => [
            'host' => 'localhost',
            /*
             * CakePHP will use the default DB port based on the driver selected
             * MySQL on MAMP uses port 8889, MAMP users will want to uncomment
             * the following line and set the port accordingly
             */
            //'port' => 'non_standard_port_number',

            'username' => 'my_app',
            'password' => 'secret',

            'database' => 'my_app',
            /*
             * If not using the default 'public' schema with the PostgreSQL driver
             * set it here.
             */
            //'schema' => 'myapp',

            /*
             * You can use a DSN string to set the entire configuration
             */
            'url' => env('DATABASE_URL', 'sqlite://127.0.0.1/tmp/recrouter.sqlite'),
        ],

        /*
         * The test connection is used during the test suite.
         */
        'test' => [
            'host' => 'localhost',
            //'port' => 'non_standard_port_number',
            'username' => 'my_app',
            'password' => 'secret',
            'database' => 'test_myapp',
            //'schema' => 'myapp',
            'url' => env('DATABASE_TEST_URL', 'sqlite://127.0.0.1/tmp/tests.sqlite'),
        ],
    ],

    /*
     * Email configuration.
     *
     * Host and credential configuration in case you are using SmtpTransport
     *
     * See app.php for more configuration options.
     */
    'EmailTransport' => [
        'default' => [
            'host' => 'localhost',
            'port' => 25,
            'username' => null,
            'password' => null,
            'client' => null,
            'url' => env('EMAIL_TRANSPORT_DEFAULT_URL', null),
        ],
        'registration' => [
            'className' => \Cake\Mailer\Transport\SmtpTransport::class,
            'host' => env('EMAIL_HOST', 'localhost'),
            'port' => (int)env('EMAIL_PORT', 25),
            'username' => env('EMAIL_USERNAME', null),
            'password' => env('EMAIL_PASSWORD', null),
            'tls' => filter_var(env('EMAIL_TLS', false), FILTER_VALIDATE_BOOLEAN),
        ],
    ],
    'CPanel' => [
        'ip' => env('CPANEL_IP', ''),
        'port' => env('CPANEL_PORT', '2083'),
        'root_user' => env('CPANEL_ROOT_USER', ''),
        'root_pass' => env('CPANEL_ROOT_PASS', ''),
        'domain' => env('CPANEL_DOMAIN', ''),
        'std_pass' => env('CPANEL_STD_PASS', ''),
        'std_quota' => env('CPANEL_STD_QUOTA', '0'),
        'pipe_fwd' => env('CPANEL_PIPE_FWD', 'php/piper.php'),
    ],
    'CloudConvert' => [
        'apiKey' => env('CLOUDCONVERT_API_KEY', ''),
    ],
    'ResumeParsers' => [
        'sovrenAccountId' => env('SOVREN_ACCOUNT_ID', ''),
        'sovrenServiceKey' => env('SOVREN_SERVICE_KEY', ''),
        'sovrenWsdl' => env('SOVREN_WSDL', 'http://services.resumeparsing.com/ParsingService.asmx?WSDL'),
    ],
];
