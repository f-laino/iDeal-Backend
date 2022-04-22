<?php
    return [
        'url' => env('CDK_URL', "https://stage.apigw.cdkapps.eu"),
        'api_version' => 'v1',
        'service' => 'sales',
        'client_id' => env('CDK_CLIENT_ID', ''),
        'client_secret' => env('CDK_CLIENT_SECRET', ''),
    ];
