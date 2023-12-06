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

use LA09R\StarterKit\UI\Vue\Inertia\App\Providers\RouteServiceProvider;

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
            Route::get(RouteServiceProvider::HOME . '/profile', [ UserController::class, 'webProfile' ])->name('users.' . RouteServiceProvider::PREFIX_WEB_HOME . '.profile');
        });

        $routes = [
            [ 'method' => 'get',  'url' => RouteServiceProvider::HOME . '/welcome',   'handler' => [ DashboardController::class, 'welcome' ], 'name' => 'users.' . RouteServiceProvider::PREFIX_WEB_HOME . '.welcome'],
            [ 'method' => 'get',  'url' => RouteServiceProvider::HOME . '/user/list', 'handler' => [ UserController::class,      'webList' ], 'name' => 'users.' . RouteServiceProvider::PREFIX_WEB_HOME . '.user.list'],
            [ 'method' => 'get',  'url' => RouteServiceProvider::HOME . '/role/list', 'handler' => [ RoleController::class,      'webList' ], 'name' => 'users.' . RouteServiceProvider::PREFIX_WEB_HOME . '.role.list'],
        ];

        Route::middleware(['auth', 'can:isUserAccess'])->group(function () use($routes) {
            RouteServiceProvider::createRoutes($routes);
        });

        // auth services
        Route::get(config('authservices.app.auth'), function ($service_id, $service_user_id) {
            if(!self::setConfig($service_id, $service_user_id))
            {
                return redirect(route(str_replace('/', '', RouteServiceProvider::LOGIN)));
            }

            if(in_array($service_id, ['github']))
            {
                return Socialite::driver($service_id)->redirect();
            }

                return redirect(route('main.' . RouteServiceProvider::PREFIX_WEB . '.public'));
        })->name('users.' . RouteServiceProvider::PREFIX_API_HOME . '.service.auth');
        Route::get(config('authservices.app.login'), function ($service_id, $service_user_id) {
            if(!self::setConfig($service_id, $service_user_id))
            {
                return redirect(route(str_replace('/', '', RouteServiceProvider::LOGIN)));
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
                return redirect(route('main.' . RouteServiceProvider::PREFIX_WEB . '.public'));
            }

            $user->update([
                'email'    => $userService->email,
                'avatar'   => $userService->avatar,
            ]);
            Auth::login(\App\Models\User::where(['id' => $user->user_id])->first());

            return redirect(route('main.' . RouteServiceProvider::PREFIX_WEB . '.dashboard'));
        })->name('users.' . RouteServiceProvider::PREFIX_API_HOME . '.service.login');
    }

    public static function apiRoutes()
    {
        Route::get(RouteServiceProvider::BACK . '/nav/auth', [ NavController::class, 'apiAuth' ])->name('users.api.route.nav.auth');

        $routes = [
            [ 'method' => 'get',  'url' => RouteServiceProvider::BACK . '/user/select/{id}', 'handler' => [ UserController::class, 'apiSelect' ], 'name' => 'users.' . RouteServiceProvider::PREFIX_API_HOME . '.user.select'],
            [ 'method' => 'post', 'url' => RouteServiceProvider::BACK . '/user/update',      'handler' => [ UserController::class, 'apiUpdate' ], 'name' => 'users.' . RouteServiceProvider::PREFIX_API_HOME . '.user.update'],
        ];

        Route::middleware(['auth:sanctum'])->group(function () use($routes) {
            RouteServiceProvider::createRoutes($routes);
        });

        $routes = [
            [ 'method' => 'get',  'url' => RouteServiceProvider::BACK . '/user/new',    'handler' => [ UserController::class, 'apiNew' ],    'name' => 'users.' . RouteServiceProvider::PREFIX_API_HOME . '.user.new'],
            [ 'method' => 'get',  'url' => RouteServiceProvider::BACK . '/user/list',   'handler' => [ UserController::class, 'apiList' ],   'name' => 'users.' . RouteServiceProvider::PREFIX_API_HOME . '.user.list'],
            [ 'method' => 'post', 'url' => RouteServiceProvider::BACK . '/user/insert', 'handler' => [ UserController::class, 'apiInsert' ], 'name' => 'users.' . RouteServiceProvider::PREFIX_API_HOME . '.user.insert'],
            [ 'method' => 'post', 'url' => RouteServiceProvider::BACK . '/user/delete', 'handler' => [ UserController::class, 'apiDelete' ], 'name' => 'users.' . RouteServiceProvider::PREFIX_API_HOME . '.user.delete'],

            [ 'method' => 'get',  'url' => RouteServiceProvider::BACK . '/role/select/{id}', 'handler' => [ RoleController::class, 'apiSelect' ], 'name' => 'users.' . RouteServiceProvider::PREFIX_API_HOME . '.role.select'],
            [ 'method' => 'get',  'url' => RouteServiceProvider::BACK . '/role/list',        'handler' => [ RoleController::class, 'apiList' ],   'name' => 'users.' . RouteServiceProvider::PREFIX_API_HOME . '.role.list'],
            [ 'method' => 'post', 'url' => RouteServiceProvider::BACK . '/role/insert',      'handler' => [ RoleController::class, 'apiInsert' ], 'name' => 'users.' . RouteServiceProvider::PREFIX_API_HOME . '.role.insert'],
            [ 'method' => 'post', 'url' => RouteServiceProvider::BACK . '/role/update',      'handler' => [ RoleController::class, 'apiUpdate' ], 'name' => 'users.' . RouteServiceProvider::PREFIX_API_HOME . '.role.update'],
            [ 'method' => 'post', 'url' => RouteServiceProvider::BACK . '/role/delete',      'handler' => [ RoleController::class, 'apiDelete' ], 'name' => 'users.' . RouteServiceProvider::PREFIX_API_HOME . '.role.delete'],
        ];

        Route::middleware(['auth:sanctum', 'can:isUserAccess'])->group(function () use($routes) {
            RouteServiceProvider::createRoutes($routes);
        });
    }
}