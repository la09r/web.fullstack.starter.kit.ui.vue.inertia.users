<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
