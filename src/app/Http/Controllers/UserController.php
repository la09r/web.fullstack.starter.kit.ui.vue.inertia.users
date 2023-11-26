<?php

namespace LA09R\StarterKit\UI\Vue\Inertia\Users\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class UserController extends Controller
{
    public function webList(Request $request)
    {
        return Inertia::render('UserList/Index', [

        ]);
    }

    public function webProfile(Request $request)
    {
        return Inertia::render('UserProfile/Index', [

        ]);
    }

    public function apiList(Request $request)
    {
        $filter = [];
        $data = User::where('id', '>', 0)->orderByDesc('id')->get();

        return $request->wantsJson() ? new JsonResponse($data, 200) : redirect('/');
    }

    public function webEdit(Request $request)
    {
        return Inertia::render('UserEdit/Index', [

        ]);
    }

    public function apiDelete(Request $request)
    {
        $id = $request->id;
        User::destroy($id);
        return $request->wantsJson() ? new JsonResponse(['id' => $id], 200) : redirect('/dashboard/user/list');
    }

    public function apiSelect(Request $request, $id)
    {
        $user = User::find($id);

        return $request->wantsJson() ? new JsonResponse($user, 200) : redirect('/dashboard/user/list');
    }

    public function apiUpdate(Request $request)
    {
        $id = $request->id;
        User::find($id)->update($request->all());
        return $request->wantsJson() ? new JsonResponse(['id' => $id], 200) : redirect('/dashboard/user/list');
    }

    public function apiInsert(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        $validator->validate();

        $createFunction = function () use($data) {
            return User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);
        };
        event(new Registered($user = $createFunction()));

        return $request->wantsJson() ? new JsonResponse([], 200) : redirect('/dashboard/user/list');
    }
}