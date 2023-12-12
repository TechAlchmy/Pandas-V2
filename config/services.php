<?php

use Aws\SecretsManager\SecretsManagerClient;
use Aws\Exception\AwsException;
use Aws\Sts\StsClient;
use Aws\S3\S3Client;

// Create a S3Client
$s3 = new S3Client([
    'version' => 'latest',
    'region' => 'us-east-2', // e.g., 'us-west-2'
]);

$bucketName = 'panda-prod-certs';
$key = 'stag.12'; // the key of the file in the S3 bucket
$saveAs = '/testCert/stag.12'; // local path to save the file

try {
    // Download the file
    $result = $s3->getObject([
        'Bucket' => $bucketName,
        'Key' => $key,
        'SaveAs' => $saveAs
    ]);

    echo "File downloaded successfully.\n";
} catch (AwsException $e) {
    // output error message if fails
    echo $e->getMessage();
    echo "\n";
}


$blackhawk_cert_pw = null;
$blackhawk_cert_url = null;
if (env("APP_ENV") === "production") {
    $blackhawk_cert_url = "/testCert/stag.12";
    $stsClient = new StsClient([
        'version' => 'latest',
        'region' => "us-east-2"
    ]);


    // Assume the IAM role
    $result = $stsClient->assumeRole([
        'RoleArn' => "arn:aws:iam::891985934622:role/RoleToRetrieveSecretAtRuntime",
        'RoleSessionName' => 'session-' . time() // You can customize the session name
    ]);

    $credentials = $result['Credentials'];

    // Create SecretsManagerClient with the assumed role credentials
    $secretsManagerClient = new SecretsManagerClient([
        'version' => 'latest',
        'region' => "us-east-2",
        'credentials' => [
            'key' => $credentials['AccessKeyId'],
            'secret' => $credentials['SecretAccessKey'],
            'token' => $credentials['SessionToken']
        ]
    ]);

    // Retrieve the secret
    $response = $secretsManagerClient->getSecretValue([
        'SecretId' => 'BLACKHAWKProd',
        'VersionStage' => 'AWSCURRENT'
    ]);

    if (isset($response['SecretString'])) {
        $blackhawk_cert_pw = $response['SecretString'];
    } else {
        $blackhawk_cert_pw = base64_decode($response['SecretBinary']);
    }
} else {
    $blackhawk_cert_pw = "BH3F2FDP7J4ZXJV3PB1CFM1M4C";
    $blackhawk_cert_url = public_path("key/stag.p12");
}


return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'cardknox' => [
        'ifields' => [
            'key' => env('CARDKNOX_IFIELDS_KEY'),
            'version' => '2.15.2302.0801',
        ],
        'transaction_key' => env('CARDKNOX_TRANSACTION_KEY'),
    ],

    'blackhawk' => [
        'base_url' => env('BLACKHAWK_BASE_URL', 'https://apipp.blackhawknetwork.com'),
        'catalog_api' => env('BLACKHAWK_BASE_URL', 'https://apipp.blackhawknetwork.com')
            . '/rewardsCatalogProcessing/v1/clientProgram/byKey',
        'realtime_order_api' => env('BLACKHAWK_BASE_URL', 'https://apipp.blackhawknetwork.com')
            . '/rewardsOrderProcessing/v1/submitRealTimeEgiftBulk',
        'bulk_order_api' => env('BLACKHAWK_BASE_URL', 'https://apipp.blackhawknetwork.com')
            . '/rewardsOrderProcessing/v1/submitEgiftBulk',

        'retreive_card_api' => env('BLACKHAWK_BASE_URL', 'https://apipp.blackhawknetwork.com')
            . '/rewardsOrderProcessing/v1/eGiftBulkCodeRetrievalInfo/byKeys',

        'client_program_id' => env('BLACKHAWK_CLIENT_PROGRAM_ID', 95006442),
        'merchant_id' => env('BLACKHAWK_MERCHANT_ID', 60300004707),
        'cert' => env('BLACKHAWK_CERT', $blackhawk_cert_url),
        'cert_password' => env('BLACKHAWK_CERT_PASSWORD', $blackhawk_cert_pw),
    ],
];
