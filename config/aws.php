<?php

use Aws\Laravel\AwsServiceProvider;

return [

    /*
    |--------------------------------------------------------------------------
    | AWS SDK Configuration
    |--------------------------------------------------------------------------
    |
    | The configuration options set in this file will be passed directly to the
    | `Aws\Sdk` object, from which all client objects are created. The minimum
    | required options are declared here, but the full set of possible options
    | are documented at:
    | http://docs.aws.amazon.com/aws-sdk-php/v3/guide/guide/configuration.html
    |
    */


        'credentials' => [
            'key'    => env('S3_KEY'),
            'secret' => env('S3_SECRET'),
        ],
        'region' => env('S3_REGION'),
        'version' => 'latest',
        'http' => [ 'verify' => false ]

];
