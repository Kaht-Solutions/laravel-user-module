<?php

namespace Modules\User\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Modules\Auth\Models\User;
use Modules\Location\Models\Province;
use Redirect;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Validator;

/**
 * @group  User Module
 *
 * Web routes for managing users , roles and permissions
 */
class UserController extends Controller
{

    /**
     * Index of admin users
     * @queryParam  type The name of a role to filter. Example: admin
     * @authenticated
     * @param Request
     * @response View
     */
    public function index(Request $request): View
    {
        $users = User::
            when($request->filled('type'), function ($query) use ($request) {
                $query->whereHas('roles', function ($sub_query) use ($request) {
                    $sub_query->where('name', $request->type);
                });
            })
            ->with(['roles.permissions'])
            ->get();

        $roles = Role::all();

        return view('user::' . env("ADMIN_THEME") . '.user.index')->with(['users' => $users, 'roles' => $roles]);
    }

    /**
     * Index of admin panel
     * @return Response
     */
    public function adminindex()
    {
        return view('user::' . env("ADMIN_THEME") . '.adminindex');
    }

    /**
     * Passport generated page for clients and tokens
     * @return Response
     */
    public function passport()
    {
        return view('user::' . env("ADMIN_THEME") . '.passport');
    }

    /**
     * Show the form for creating a new User.
     */
    public function create()
    {
        $roles = Role::all();
        $provinces = Province::all();
        return view('user::' . env("ADMIN_THEME") . '.user.create', compact('roles', 'provinces'));
    }

    /**
     * Store a newly created User in database.
     * @bodyParam name required name of the user
     * @bodyParam family required family of the user
     * @bodyParam mobile required mobile of the user
     * @bodyParam password required password of the user
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $valid = Validator::make($data, [
            'name' => 'required|max:255',
            'family' => 'required|max:255',
            'mobile' => 'required|regex:/^[0][9][0-9]{9,9}$/|unique:user_module_users',
            'password' => 'required|min:6',
        ]);

        if ($valid->fails()) {
            return Redirect::back()->withErrors($valid)->withInput();
        }

        $user = new User;
        $user->name = $request->input('name');
        $user->family = $request->input('family');
        $user->mobile = $request->input('mobile');
        $user->password = bcrypt($request->input('password'));
        $user->province_id = $request->input('province_id');

        $user->save();

        return Redirect::back()->withErrors(trans('user::messages.done'));
    }

    /**
     * Show the specified User.
     * @return View
     */
    public function show(Request $request)
    {
        if ($request->id == 5 || $request->id == 7) {
            abort(403);
        }

        $user = User::find($request->input('id'));
        $roles = Role::all();
        $permissions = Permission::all();

        return view('user::' . env("ADMIN_THEME") . '.user.show')->with(['user' => $user, 'permissions' => $permissions, 'roles' => $roles]);
    }

    /**
     * Show the form for editing the specified User.
     * @return Response
     */
    public function edit(Request $request)
    {
        if ($request->id == 5 || $request->id == 7) {
            abort(403);
        }

        $user = User::find($request->input('id'));
        $roles = Role::all();
        $provinces = Province::all();
        return view('user::' . env("ADMIN_THEME") . '.user.edit', compact('user', 'roles', 'provinces'));
    }

    /**
     * Update the specified User in database.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request)
    {
        if ($request->id == 1 || $request->id == 2) {
            //  abort(403);
        }

        $data = $request->all();
        $valid = Validator::make($data, [
            'name' => 'required|max:255',
            'family' => 'required|max:255',
            'mobile' => 'required|regex:/^[0][9][0-9]{9,9}$/|unique:user_module_users,mobile,' . $request->input('id') . '|',
        ]);

        if ($valid->fails()) {
            return Redirect::back()->withErrors($valid)->withInput();
        }

        $user = User::find($request->input('id'));
        $user->name = $request->input('name');
        $user->family = $request->input('family');
        $user->mobile = $request->input('mobile');
        $user->province_id = $request->input('province_id');

        if ($request->input('password')) {
            $user->password = bcrypt($request->input('password'));
        }
        $user->save();

        return Redirect::back()->withErrors(trans('user::messages.done'));
    }

    /**
     * Remove the specified User from database.
     * @return Response
     */
    public function destroy(Request $request)
    {
        if ($request->id == 1 || $request->id == 2) {
            // abort(403);
        }

        $user = User::find($request->input('id'));
        $user->delete();

        return Redirect::back()->withErrors(trans('user::messages.done'));
    }

    public function setrole(Request $request)
    {

        // return $request->all();
        $role = $request->input('role');

        $user = $request->input('user');
        if ($request->ajax() && $role) {
            $user = User::find($user);

            $role = Role::whereIn('id', $role)->get();

            $user->syncRoles($role);
            return trans('user::messages.done');
        } elseif (!$role) {
            $user = User::find($user);
            $user->syncRoles([]);

            return trans('user::messages.done');
        }
        return trans('user::messages.error');
    }
    public function setpermission(Request $request)
    {
        if ($request->filled('role')) {
            $role = $request->input('role');
            $permission = $request->input('permission');
            if ($request->ajax() && $permission) {
                $role = Role::find($role);
                $permission = Permission::whereIn('id', $permission)->get();

                $role->syncPermissions($permission);
                return trans('user::messages.done');
            } elseif (!$permission) {
                $role = Role::find($role);
                $role->syncPermissions([]);

                return trans('user::messages.done');
            }
        } elseif ($request->filled('user')) {
            $user = $request->input('user');
            $permission = $request->input('permission');
            if ($request->ajax() && $permission) {
                $user = User::find($user);
                $permission = Permission::whereIn('id', $permission)->get();

                $user->syncPermissions($permission);
                return trans('user::messages.done');
            } elseif (!$permission) {
                $user = Role::find($user);
                $user->syncPermissions([]);

                return trans('user::messages.done');
            }
        }

        return trans('user::messages.error');
    }

    public function generate_permissions()
    {
        $routeCollection = \Route::getRoutes()->getIterator();
        $total = [];
        $new = $updated = $deleted = 0;
        $route_uris = [];

        foreach ($routeCollection as $route) {
            if (strpos($route->uri, 'admin') !== false) {
                $uri_array = explode('/', $route->uri);
                $display_name_array = [];
                foreach ($uri_array as $obj) {
                    if ($obj) {
                        $display_name_array[] = trans('theme::routing.' . $obj);
                    }
                }
                $display_name = implode(' -> ', $display_name_array);
                $route_uris[] = $route->uri;

                $permission = Permission::where('name', $route->uri)->first();
                if ($permission) {
                    $permission->display_name = $display_name;
                    $permission->save();
                    $updated++;
                } else {
                    $permission = new Permission;
                    $permission->name = $route->uri;
                    $permission->display_name = $display_name;
                    $total[] = $permission;
                    $permission->save();
                    $new++;
                }
            }
        }

        $permissions = Permission::pluck('name')->toArray();
        $not_exist = array_diff($permissions, $route_uris);
        if ($not_exist) {
            Permission::whereIn('name', $not_exist)->delete();
        }
        $deleted = count($not_exist);

        return Redirect::back()->withErrors([
            "<h5>" . trans('user::messages.generate_permission') . "</h5>" .
            trans("user::messages.total") . " : " . count($total) . "<br>" .
            trans("user::messages.new") . " : " . $new . "<br>" .
            trans("user::messages.updated") . " : " . $updated . "<br>" .
            trans("user::messages.deleted") . " : " . $deleted . "<br>",
        ]);
    }
}
