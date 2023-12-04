<?php

namespace LA09R\StarterKit\UI\Vue\Inertia\Users\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

use LA09R\StarterKit\UI\Vue\Inertia\Users\App\Models\User as UserPackage;
use LA09R\StarterKit\UI\Vue\Inertia\Users\App\Models\UsersAuthService;

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
            'text' => 'Test UserProfile/Index',
        ]);
    }

    public function apiList(Request $request)
    {
        $filter = [];
        $data = UserPackage::where('id', '>', 0)->orderByDesc('id')->get();

        foreach ($data as &$user)
        {
            $user->role_name = $user->role->name;
        }

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

    public function apiNew(Request $request)
    {
        $user = new \stdClass();
        $roles = app(RoleController::class)->apiList($request, true);

        $user->role_list     = $roles;

        return $request->wantsJson() ? new JsonResponse($user, 200) : redirect('/dashboard/user/list');
    }

    public function apiSelect(Request $request, $id)
    {
        if( !Gate::allows('isUserAccessCurrent', (int)$id) && !Gate::allows('isUserAccess') )
        {
            abort(403);
        }
        $user      = User::find($id);
        $userRoles = Gate::allows('isUserAccess', (int)$id) ? null : [$user->role_id];

        $roles = app(RoleController::class)->apiList($request, true, $userRoles);

        $role = array_filter($roles->toArray(), function ($role) use($user) {
            return $user->role_id == $role['id'];
        });

        $userAuthServicesSelected = [];
        $userAuthServices = UsersAuthService::where(['user_id' => $id,])->get()->toArray();
        $userAuthServices = array_map(function ($userAuthService) use(&$userAuthServicesSelected) {
            $userAuthServicesSelected[] = $userAuthService['service_id'];
            return [
                'selected'      => true,
                'name'          => $userAuthService['name'],
                'service_id'    => $userAuthService['service_id'],
                'json'          => $userAuthService['secrets'],
            ];
        }, $userAuthServices);

        $user->auth_services_template = array_map(function ($item) use($userAuthServicesSelected) {
            $item['selected'] = in_array($item['service_id'], $userAuthServicesSelected);
            try
            {
                $item['json'] = file_get_contents(__DIR__ . '/../../../resources/json/template/auth.' . $item['service_id'] . '.json');
            }
            catch (\Exception | \Error $e)
            {
                $item['json'] = '';
            }
            return $item;
        }, UsersAuthService::SERVICES_SECRETS_TEMPALTE);
        $user->auth_services = $userAuthServices;
        $user->role_selected = array_shift($role);
        $user->role_list     = $roles;

        return $request->wantsJson() ? new JsonResponse($user, 200) : redirect('/dashboard/user/list');
    }

    public function apiUpdate(Request $request)
    {
        $id   = $request->id;
        $post = $request->all();
        $data = [];

        if( !Gate::allows('isUserAccessCurrent', (int)$id) && !Gate::allows('isUserAccess') )
        {
            abort(403);
        }

        array_walk($post, function ($value, $key) use(&$data) {
            if(!empty($value))
            {
                $data[ $key ] = $value;
            }
        });
                               UsersAuthService::where(['user_id' => $id])->delete();
        foreach ($post['auth_services'] ?? [] as $item)
        {
            try
            {
                $json = json_decode(str_replace([' ', "\n"], ['', ''], $item['value']), true);
            }
            catch (\Exception | \Error $e)
            {
                $json = '';
            }

            $userAuthService = UsersAuthService::create([
                'user_id'       => $id,
                'service_id'    => $item['service_id'],
                'secrets'       => $item['value'], // str_replace(["\n", ' '], [''], $item['value']),
                'name'          => $item['name'],
                'login'         => $json['client_username'] ?? '',
            ]);
        }

        UserPackage::find($id)->update($data);
        return $request->wantsJson() ? new JsonResponse(['id' => $id], 200) : redirect('/dashboard/user/list');
    }

    public function apiInsert(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role_id'  => ['numeric'],
        ]);
        $validator->validate();

        $createFunction = function () use($data) {
            return UserPackage::create([
                'role_id'   => $data['role_id'],
                'name'      => $data['name'],
                'email'     => $data['email'],
                'password'  => Hash::make($data['password']),
            ]);
        };
        event(new Registered($user = $createFunction()));

        return $request->wantsJson() ? new JsonResponse([], 200) : redirect('/dashboard/user/list');
    }
}