<?php

namespace Modules\User\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Redirect;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Validator;
use \Modules\Location\Models\Province;

/**
 * @group  User Module
 *
 * APIs for managing users , roles and permissions
 */
class RoleController extends Controller
{
    public function Index()
    {
        $roles = Role::get();

        $permissions = Permission::all();

        $permission_groups = $permission_group_name = [];

        foreach ($permissions as $permission) {
            $name_exploded = explode('/', $permission->name);
            if (isset($name_exploded[1]) && ($name_exploded[1] == "user" || $name_exploded[1] == "blog")) {
                $group_name = implode('/', [$name_exploded[0], $name_exploded[1]]);
            } elseif (isset($name_exploded[2])) {
                $group_name = implode('/', [$name_exploded[0], $name_exploded[1], $name_exploded[2]]);
            } elseif (isset($name_exploded[1])) {
                $group_name = implode('/', [$name_exploded[0], $name_exploded[1]]);
            }

            if (!in_array($group_name, $permission_group_name)) {
                $permission_group_name[] = $group_name;
            }

            $uri_array = explode('/', $group_name);
            $display_name_array = [];
            foreach ($uri_array as $obj) {
                if ($obj) {
                    $display_name_array[] = trans('theme::routing.' . $obj);
                }
            }
            $display_name = implode(' -> ', $display_name_array);
            $permission_groups[$group_name]['name'] = $display_name;
            $permission_groups[$group_name]['permissions'][] = $permission;
        }

        return view('user::' . env("ADMIN_THEME") . '.role.index')
            ->with('roles', $roles)
            ->with('permissions', $permissions)
            ->with('permission_groups', $permission_groups);
    }

    public function Show()
    {
        $role = Role::find(Request::get('id'));

        return view('user::' . env("ADMIN_THEME") . '.role.show')->with('role', $role);
    }

    public function Create()
    {
        $provinces = Province::all();
        return view('user::' . env("ADMIN_THEME") . '.role.create', ['provinces' => $provinces]);
    }

    public function Store(Request $request)
    {
        $data = $request->all();
        $valid = Validator::make($data, [
            'name' => 'required|max:255|unique:usermodule_roles',
            'display_name' => 'required|max:255|unique:usermodule_roles']);

        if ($valid->fails()) {
            return Redirect::back()->withErrors($valid)->withInput();
        }

        $role = new Role;
        $role->name = $data['name'];
        $role->display_name = $data['display_name'];
        $role->save();

        return Redirect::back()->withErrors(trans('user::messages.done'));
    }

    public function Edit(Request $request)
    {
        $id = $request->input('id');
        $role = Role::find($id);
        $provinces = Province::all();
        return view('user::' . env("ADMIN_THEME") . '.role.edit')->with('role', $role)->with('provinces', $provinces);
    }

    public function Update(Request $request)
    {
        $data = $request->all();
        $valid = Validator::make($data, [
            'name' => 'required|max:255|unique:usermodule_roles,name,' . $data['id'],
            'display_name' => 'required|max:255|unique:usermodule_roles,display_name,' . $data['id']]);

        if ($valid->fails()) {
            return Redirect::back()->withErrors($valid)->withInput();
        }

        $id = $request->input('id');
        $role = Role::find($id);

        $role->name = $data['name'];
        $role->display_name = $data['display_name'];
        $role->save();

        return Redirect::back()->withErrors(trans('user::messages.done'));
    }

    public function Destroy(Request $request)
    {
        $id = $request->input('id');
        $role = Role::find($id);
        $role->delete();

        return Redirect::back()->withErrors(trans('user::messages.done'));
    }
}
