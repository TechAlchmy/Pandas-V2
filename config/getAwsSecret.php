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
