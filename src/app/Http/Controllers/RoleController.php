<?php

namespace LA09R\StarterKit\UI\Vue\Inertia\Users\App\Http\Controllers;

use App\Http\Controllers\Controller;
use LA09R\StarterKit\UI\Vue\Inertia\Users\App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class RoleController extends Controller
{
    public function webList(Request $request)
    {
        return Inertia::render('RoleList/Index', [

        ]);
    }

    public function apiList(Request $request, $raw = false, $roles = null)
    {
        $filter = [];

        if(!$roles)
        {
            $data = Role::where('id', '>', 0)->get();
        }
        else
        {
            $data = Role::whereIn('id', $roles)->get();
        }

        if($raw) { return $data; }

        return $request->wantsJson() ? new JsonResponse($data, 200) : redirect('/');
    }

    public function apiDelete(Request $request)
    {
        $id = $request->id;
        Role::destroy($id);
        return $request->wantsJson() ? new JsonResponse(['id' => $id], 200) : redirect('/dashboard/role/list');
    }

    public function apiSelect(Request $request, $id)
    {
        $role = Role::find($id);

        return $request->wantsJson() ? new JsonResponse($role, 200) : redirect('/dashboard/role/list');
    }

    public function apiUpdate(Request $request)
    {
        $id = $request->id;
        Role::find($id)->update($request->all());
        return $request->wantsJson() ? new JsonResponse(['id' => $id], 200) : redirect('/dashboard/role/list');
    }

    public function apiInsert(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'name'  => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255', 'unique:roles'],
        ]);
        $validator->validate();

        $role = Role::create([
            'name' => $data['name'],
            'code' => $data['code'],
        ]);

        return $request->wantsJson() ? new JsonResponse($role, 200) : redirect('/dashboard/role/list');
    }
}