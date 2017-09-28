<?php

namespace NTI\Providers;

use Storage;
use League\Flysystem\Filesystem;
use League\Flysystem\Azure\AzureAdapter;
use MicrosoftAzure\Storage\Common\ServicesBuilder;

use Illuminate\Support\ServiceProvider;

class AzureStorageServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Storage::extend('azure', function($app, $config) {
            $endpoint = sprintf(
                'DefaultEndpointsProtocol=https;AccountName=%s;AccountKey=%s',
                $config['name'],
                $config['key']
            );
            $blobRestProxy = ServicesBuilder::getInstance()->createBlobService($endpoint);
            return new Filesystem(new AzureAdapter($blobRestProxy, $config['container']));
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
