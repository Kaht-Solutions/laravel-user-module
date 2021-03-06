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
class PermissionController extends Controller
{

    public function index()
    {
        $permissions = Permission::all();

        return view('user::' . env("ADMIN_THEME") . '.permission.index')->with('permissions', $permissions);
    }

    public function show()
    {
        $permission = Permission::find(Request::get('id'));

        return view('user::' . env("ADMIN_THEME") . '.permission.show')->with('permission', $permission);
    }

    public function create()
    {
        $provinces = Province::all();
        return view('user::' . env("ADMIN_THEME") . '.permission.create', ['provinces' => $provinces]);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $valid = Validator::make($data, [
            'name' => 'required|max:255|unique:usermodule_permissions,name',
            'display_name' => 'required|max:255|unique:usermodule_permissions,display_name'
        ]);

        if ($valid->fails()) {
            return Redirect::back()->withErrors($valid)->withInput();
        }

        $permission = new Permission;
        $permission->name = $data['name'];
        $permission->display_name = $data['display_name'];
        $permission->save();

        return Redirect::back()->withErrors(trans('user::messages.done'));
    }

    public function edit(Request $request)
    {
        $id = $request->input('id');
        $permission = Permission::find($id);
        $provinces = Province::all();
        return view('user::' . env("ADMIN_THEME") . '.permission.edit')->with('permission', $permission)->with('provinces', $provinces);
    }

    public function update(Request $request)
    {
        $data = $request->all();
        $valid = Validator::make($data, [
            'name' => 'required|max:255|unique:usermodule_permissions,name,' . $data['id'],
            'display_name' => 'required|max:255|unique:usermodule_permissions,display_name,' . $data['id']
        ]);

        if ($valid->fails()) {
            return Redirect::back()->withErrors($valid)->withInput();
        }

        $id = $request->input('id');
        $permission = Permission::find($id);

        $permission->name = $data['name'];
        $permission->display_name = $data['display_name'];
        $permission->save();

        return Redirect::back()->withErrors(trans('user::messages.done'));
    }

    public function destroy(Request $request)
    {
        $id = $request->input('id');
        $permission = Permission::find($id);
        $permission->delete();

        return Redirect::back()->withErrors(trans('user::messages.done'));
    }
}
