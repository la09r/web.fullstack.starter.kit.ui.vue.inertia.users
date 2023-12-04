<?php

namespace LA09R\StarterKit\UI\Vue\Inertia\Users\App;

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use LA09R\StarterKit\UI\Vue\Inertia\Users\App\Http\Controllers\DashboardController;
use LA09R\StarterKit\UI\Vue\Inertia\Users\App\Http\Controllers\NavController;
use LA09R\StarterKit\UI\Vue\Inertia\Users\App\Models\UsersAuthService;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;

use LA09R\StarterKit\UI\Vue\Inertia\Users\App\Http\Controllers\UserController;
use LA09R\StarterKit\UI\Vue\Inertia\Users\App\Http\Controllers\RoleController;

use LA09R\StarterKit\UI\Vue\Inertia\Users\App\Models\User as UserPackage;

class Router
{
    private static function getAuthServiceLoginUrl($service, $login, $url)
    {
        $url =  str_replace(['{service_id}', '{service_user_id}'], [$service, $login], $url);

        return $url;
    }

    private static function setConfig($service, $login)
    {
        try
        {
            $userAuthService = UsersAuthService::where(['service_id' => $service, 'login' => $login])->first()->toArray();
        }
        catch (\Exception | \Error $e)
        {
            $userAuthService = null;
        }

        if(!$userAuthService)
        {
            return false;
        }

        $secrets         = json_decode(str_replace(["\n", ' '], [''], $userAuthService['secrets']), true);

        config(['services.github.client_id'     => $secrets['client_id']]);
        config(['services.github.client_secret' => $secrets['client_secret']]);
        config(['services.github.redirect'      => env('APP_URL') . self::getAuthServiceLoginUrl($service, $login, config('authservices.app.login') )]);
        
        return true;
    }

    public static function webRoutes()
    {
        Route::middleware(['auth'])->group(function () {
            Route::get('/dashboard/profile', [ UserController::class, 'webProfile' ])->name('web.route.dashboard.profile');
        });
        Route::middleware(['auth', 'can:isUserAccess'])->group(function () {
            Route::get('/dashboard/welcome', [ DashboardController::class, 'welcome' ])->name('route.dashboard.welcome');
            Route::get('/dashboard/user/list', [ UserController::class, 'webList' ])->name('web.route.dashboard.user.list');
            Route::get('/dashboard/role/list', [ RoleController::class, 'webList' ])->name('web.route.dashboard.role.list');
//            Route::get('/dashboard/permission', [ UserController::class, 'webPermissionList' ])->name('web.route.dashboard.permission.list');
        });

        // auth services
        Route::get(config('authservices.app.auth'), function ($service_id, $service_user_id) {
            if(!self::setConfig($service_id, $service_user_id))
            {
                return redirect(route('login'));
            }

            if(in_array($service_id, ['github']))
            {
                return Socialite::driver($service_id)->redirect();
            }

                return redirect(route('route.public'));
        });
        Route::get(config('authservices.app.login'), function ($service_id, $service_user_id) {
            if(!self::setConfig($service_id, $service_user_id))
            {
                return redirect(route('login'));
            }

            if(in_array($service_id, ['github']))
            {
                $userService = Socialite::driver($service_id)->user();
            }
            else
            {
                $userService = null;
            }

            if($service_id  == 'github' && !empty($userService))
            {
                $user = UsersAuthService::where(['service_id' => $service_id, 'login' => $userService->nickname,])->first();
            }
            else
            {
                $user = new class { public function exists() { return false; } };
            }

            if(!$user->exists())
            {
                return redirect(route('route.public'));
            }

            $user->update([
                'email'    => $userService->email,
                'avatar'   => $userService->avatar,
            ]);
            Auth::login(\App\Models\User::where(['id' => $user->user_id])->first());

            return redirect(route('route.dashboard'));
        });
    }

    public static function apiRoutes()
    {
        Route::get('/backend/nav/auth', [ NavController::class, 'apiAuth' ])->name('api.route.nav.auth');

        Route::middleware(['auth:sanctum'])->group(function () {
            Route::get('/backend/user/select/{id}', [ UserController::class, 'apiSelect' ])->name('api.route.dashboard.user.select');
            Route::post('/backend/user/update', [ UserController::class, 'apiUpdate' ])->name('api.route.dashboard.user.update');
        });

        Route::middleware(['auth:sanctum', 'can:isUserAccess'])->group(function () {
            Route::get('/backend/user/new',     [ UserController::class, 'apiNew' ])->name('api.route.dashboard.user.new');
            Route::get('/backend/user/list',    [ UserController::class, 'apiList' ])->name('api.route.dashboard.user.list');
            Route::post('/backend/user/insert', [ UserController::class, 'apiInsert' ])->name('api.route.dashboard.user.insert');
            Route::post('/backend/user/delete', [ UserController::class, 'apiDelete' ])->name('api.route.dashboard.user.delete');

            Route::get('/backend/role/select/{id}', [ RoleController::class, 'apiSelect' ])->name('api.route.dashboard.role.select');
            Route::get('/backend/role/list',    [ RoleController::class, 'apiList' ])->name('api.route.dashboard.role.list');
            Route::post('/backend/role/insert', [ RoleController::class, 'apiInsert' ])->name('api.route.dashboard.role.insert');
            Route::post('/backend/role/update', [ RoleController::class, 'apiUpdate' ])->name('api.route.dashboard.role.update');
            Route::post('/backend/role/delete', [ RoleController::class, 'apiDelete' ])->name('api.route.dashboard.role.delete');
        });
    }
}