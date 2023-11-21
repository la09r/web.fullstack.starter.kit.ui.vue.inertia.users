<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Auth::routes();

LA09R\StarterKit\UI\Vue\Inertia\App\Router::routes();
LA09R\StarterKit\UI\Vue\Inertia\Users\App\Router::webRoutes();
