<?php

require __DIR__.'/vendor/autoload.php';

use Google\Client;

$client = new Client();
$client->setClientId('532725931750-dq1cr78a29uu9apv7nrlqvebml1pg99m.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('urn:ietf:wg:oauth:2.0:oob');
$client->setAccessType('offline');
$client->setPrompt('consent');
$client->setScopes(['https://www.googleapis.com/auth/drive']);

$authUrl = $client->createAuthUrl();

echo "Abre este enlace en tu navegador:\n$authUrl\n\n";
echo "Después de autorizar la app, pega aquí el código que te dieron:\n";

$authCode = trim(fgets(STDIN));

$accessToken = $client->fetchAccessTokenWithAuthCode($authCode);

echo "\nAquí está tu refresh_token:\n";
echo $accessToken['refresh_token'] . "\n";
