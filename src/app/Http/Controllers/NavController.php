<?php

namespace LA09R\StarterKit\UI\Vue\Inertia\Users\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use LA09R\StarterKit\UI\Vue\Inertia\Users\App\Models\UsersAuthService;

class NavController extends Controller
{
    public function apiAuth(Request $request)
    {
        $data = [
            'service' => UsersAuthService::SERVICES_SECRETS_TEMPALTE,
            'app' => config('authservices.app') ?? []
        ];
        return $request->wantsJson() ? new JsonResponse($data, 200) : redirect('/');
    }
}