<?php

namespace LA09R\StarterKit\UI\Vue\Inertia\Users\App;

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

use LA09R\StarterKit\UI\Vue\Inertia\Users\App\Http\Controllers\NavController;
use LA09R\StarterKit\UI\Vue\Inertia\Users\App\Http\Controllers\PermissionController;
use LA09R\StarterKit\UI\Vue\Inertia\Users\App\Http\Controllers\RoleController;
use LA09R\StarterKit\UI\Vue\Inertia\Users\App\Http\Controllers\StatusController;
use LA09R\StarterKit\UI\Vue\Inertia\Users\App\Http\Controllers\UserController;

class Router
{
    public static function webRoutes()
    {
        Route::middleware(['auth'])->group(function () {
            Route::get('/dashboard/user/list', [ UserController::class, 'webList' ])->name('web.route.dashboard.user.list');
//            Route::get('/dashboard/role/list', [ UserController::class, 'webRoleList' ])->name('web.route.dashboard.role.list');
            Route::get('/dashboard/profile', [ UserController::class, 'webProfile' ])->name('web.route.dashboard.profile');
//            Route::get('/dashboard/permission', [ UserController::class, 'webPermissionList' ])->name('web.route.dashboard.permission.list');
        });
    }

    // curl -X "GET" "http://d.laravel.test.home.81.local/api/backend/user/list" -H "Accept: application/json"
    // php artisan route:list
    // php artisan route:list --path=api
    public static function apiRoutes()
    {
        
        Route::post('/backend/nav/logout', [ LoginController::class, 'logout' ])->name('api.route.nav.logout');

        Route::middleware(['auth:sanctum'])->group(function () {
            Route::get('/backend/user/select/{id}', [ UserController::class, 'apiSelect' ])->name('api.route.dashboard.user.select');
            Route::get('/backend/user/list',    [ UserController::class, 'apiList' ])->name('api.route.dashboard.user.list');
            Route::post('/backend/user/insert', [ UserController::class, 'apiInsert' ])->name('api.route.dashboard.user.insert');
            Route::post('/backend/user/update', [ UserController::class, 'apiUpdate' ])->name('api.route.dashboard.user.update');
            Route::post('/backend/user/delete', [ UserController::class, 'apiDelete' ])->name('api.route.dashboard.user.delete');
        });
    }
}