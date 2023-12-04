<?php

namespace LA09R\StarterKit\UI\Vue\Inertia\Users\App\Providers;

 use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
 use LA09R\StarterKit\UI\Vue\Inertia\Users\App\Models\Role;

 class AuthServiceProvider extends \App\Providers\AuthServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        $roles             = Role::all();
        $rolesUserAccess   = Role::whereIn('code', ['developer', 'owner', 'admin_super', 'admin'])->get()->toArray();
        $rolesIdUserAccess = array_column($rolesUserAccess, 'id');

        foreach ($roles as $role)
        {
            $name = str_replace([' '], [''], $role->name);

            Gate::define('is' . $name, function($user) use($role) {
                return $user->role_id == $role->id;
            });
        }
            Gate::define('isUserAccess', function($user) use($rolesIdUserAccess) {
                return in_array($user->role_id, $rolesIdUserAccess);
            });
            Gate::define('isUserAccessCurrent', function($user, int $userTargetId) use($rolesIdUserAccess) {
                return $user->id == $userTargetId;
            });
    }
}
