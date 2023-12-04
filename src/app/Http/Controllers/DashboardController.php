<?php

namespace LA09R\StarterKit\UI\Vue\Inertia\Users\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function welcome(Request $request)
    {
        return Inertia::render('Welcome/Index', [
            'app_version' => \Illuminate\Foundation\Application::VERSION,
            'php_version' => PHP_VERSION,
        ]);
    }
}