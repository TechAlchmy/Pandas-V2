<?php
use Aws\SecretsManager\SecretsManagerClient;
use Aws\Sts\StsClient;
use Aws\Exception\AwsException;

/**
 * Assumes an IAM role and retrieves a secret from AWS Secrets Manager.
 *
 *
 * @param string $secretName The name of the secret.
 * @param string $region The AWS region where the secret is stored.
 * @return string|null The retrieved secret, or null in case of failure.
 */
function getAwsSecret($secretName, $region = 'us-east-2') {
    $stsClient = new StsClient([
        'version' => 'latest',
        'region' => $region
    ]);

    try {
        // Assume the IAM role
        $result = $stsClient->assumeRole([
            'RoleArn' => "arn:aws:iam::891985934622:role/RoleToRetrieveSecretAtRuntime",
            'RoleSessionName' => 'session-' . time() // You can customize the session name
        ]);

        $credentials = $result['Credentials'];

        // Create SecretsManagerClient with the assumed role credentials
        $secretsManagerClient = new SecretsManagerClient([
            'version' => 'latest',
            'region' => $region,
            'credentials' => [
                'key' => $credentials['AccessKeyId'],
                'secret' => $credentials['SecretAccessKey'],
                'token' => $credentials['SessionToken']
            ]
        ]);

        // Retrieve the secret
        $response = $secretsManagerClient->getSecretValue([
            'SecretId' => $secretName,
            'VersionStage' => 'AWSCURRENT'
        ]);

        if (isset($response['SecretString'])) {
            return $response['SecretString'];
        } else {
            return base64_decode($response['SecretBinary']);
        }
    } catch (AwsException $e) {
        error_log($e->getMessage());
        return null;
    }
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
        'cert' => env('BLACKHAWK_CERT', public_path('key/stag.p12')),
        // 'cert_password' => env('BLACKHAWK_CERT_PASSWORD', 'BH3F2FDP7J4ZXJV3PB1CFM1M4C'),
         'cert_password' => env('APP_ENV', 'staging') == "production" ? getAwsSecret("BLACKHAWKProd", "us-east-2"): env('BLACKHAWK_CERT_PASSWORD', 'BH3F2FDP7J4ZXJV3PB1CFM1M4C'),
    ],
];
