<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\File;

class PublishSkluviu extends PublishFiles
{
    private const PATH_FROM = 'vendor/la09r/web-fullstack-starter-kit-ui-vue-inertia-users/src';
    
    private const FILES = [
        'resources/php/menu/Dashboard/users.php'                      => 'php/menu/Dashboard/users.php',
        'resources/js/ComponentsAsync/Dashboard/Widget/UsersStat.vue' => 'js/ComponentsAsync/Dashboard/Widget/UsersStat.vue',
    ];
    
    protected $signature   = 'app:publish-skluviu';
    protected $description = 'Publish done';

    public function handle()
    {
        $this->publishFiles(self::FILES, self::PATH_FROM);

        print PHP_EOL . $this->description . PHP_EOL;
    }
}
