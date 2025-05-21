<?php

namespace App\Providers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;
use Masbug\Flysystem\GoogleDriveAdapter;
use Google\Client as GoogleClient;
use Google\Service\Drive as GoogleDrive;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Filesystem\FilesystemManager;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Storage::extend('google', function ($app, $config) {
            $client = new GoogleClient();
            $client->setClientId($config['clientId']);
            $client->setClientSecret($config['clientSecret']);
            $client->refreshToken($config['refreshToken']);

            $service = new GoogleDrive($client);
            $adapter = new GoogleDriveAdapter($service, $config['folderId'] ?? null);
            $flysystem = new Filesystem($adapter); // Flysystem v2

            // ✅ Crea FilesystemAdapter de forma compatible
            return new FilesystemAdapter($flysystem, $adapter, $config);
        });
    }
}

