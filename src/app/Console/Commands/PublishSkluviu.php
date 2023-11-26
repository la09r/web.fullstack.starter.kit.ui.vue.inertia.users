<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\File;

class PublishSkluviu extends PublishFiles
{
    private const PATH_FROM = 'vendor/la09r/web-fullstack-starter-kit-ui-vue-inertia-users/src';
    
    private const FILES = [
//        'resources/js/app.js.php' => ('js/app.js'),
        

//        'resources/views/auth/nav/dashboard.blade.php' => ('views/auth/nav/dashboard.blade.php'),
//        'resources/views/layouts/auth.blade.php'       => ('views/layouts/auth.blade.php'),
//        'resources/views/layouts/dashboard.blade.php'  => ('views/layouts/dashboard.blade.php'),
//        'resources/views/layouts/public.blade.php'     => ('views/layouts/public.blade.php'),
//        'resources/views/error.blade.php'              => ('views/error.blade.php'),

        
    ];
    
    protected $signature   = 'app:publish-skluviu';
    protected $description = 'Publish done';

    public function handle()
    {
        $this->publishFiles(self::FILES, self::PATH_FROM);

        print PHP_EOL . $this->description . PHP_EOL;
    }
}
