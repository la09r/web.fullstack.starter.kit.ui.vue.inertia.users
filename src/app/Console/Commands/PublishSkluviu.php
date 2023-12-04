<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\File;

class PublishSkluviu extends PublishFiles
{
    private const PATH_FROM = 'vendor/la09r/web-fullstack-starter-kit-ui-vue-inertia-users/src';
    
    private const FILES = [
        'config/authservices.php'                                     => 'config/authservices.php',
        'app/Console/Commands/Gates.php'                              => 'app/Console/Commands/Gates.php',
        'resources/js/Components/Dashboard/AuthServices.vue'          => 'js/Components/Dashboard/AuthServices.vue',

        'resources/php/menu/Dashboard/users.php'                      => 'php/menu/Dashboard/users.php',
        'resources/js/ComponentsAsync/Dashboard/Widget/UsersStat.vue' => 'js/ComponentsAsync/Dashboard/Widget/UsersStat.vue',
    ];
    
    protected $signature   = 'app:publish-skluviu';
    protected $description = 'Publish done';

    public function handle()
    {
        $this->publishFiles(self::FILES, self::PATH_FROM);

        $this->publisRoles();
        $this->publishUserRole();

        print PHP_EOL . $this->description . PHP_EOL;
    }

    private function publisRoles()
    {
        $count = \LA09R\StarterKit\UI\Vue\Inertia\Users\App\Models\Role::all()->count();
        if($count != 0) { return; }
        
        $roles = [
            [
                'name' => 'Developer',
                'code' => 'developer',
            ],
            [
                'name' => 'Owner',
                'code' => 'owner',
            ],
            [
                'name' => 'Admin Super',
                'code' => 'admin_super',
            ],
            [
                'name' => 'Admin',
                'code' => 'admin',
            ],
            [
                'name' => 'Manager',
                'code' => 'manager',
            ],
            [
                'name' => 'User',
                'code' => 'user',
            ],
            [
                'name' => 'Client',
                'code' => 'client',
            ],
            [
                'name' => 'Contractor',
                'code' => 'contractor',
            ],
        ];

        foreach ($roles as $role)
        {
            \LA09R\StarterKit\UI\Vue\Inertia\Users\App\Models\Role::create($role);
        }
    }

    private function publishUserRole()
    {
        $user = \App\Models\User::all()->sortByDesc('id')->first();
        $user->role_id = 3;
        $user->save();
    }
}
