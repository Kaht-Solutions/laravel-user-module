<?php

namespace Modules\User\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Redirect;
use Validator;
use \Modules\Location\Models\Province;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{

    public function Index()
    {

        $permissions = Permission::all();

        return view('user::'.env("ADMIN_THEME").'.permission.index')->with('permissions', $permissions);
    }

    public function Show()
    {
        $permission = Permission::find(Request::get('id'));

        return view('user::'.env("ADMIN_THEME").'.permission.show')->with('permission', $permission);

    }

    public function Create()
    {
        $provinces=Province::all();
        return view('user::'.env("ADMIN_THEME").'.permission.create',['provinces'=>$provinces]);
    }

    public function Store(Request $request)
    {

        $data = $request->all();
        $valid = Validator::make($data, [
            'name' => 'required|max:255|unique:usermodule_permissions',
            'display_name' => 'required|max:255|unique:usermodule_permissions']);

        if ($valid->fails()) {
            return Redirect::back()->withErrors($valid)->withInput();
        }

        $permission = new Permission;
        $permission->name = $data['name'];
        $permission->display_name = $data['display_name'];
        $permission->save();

        return Redirect::back()->withErrors(trans('user::messages.done'));
    }

    public function Edit(Request $request)
    {
        $id = $request->input('id');
        $permission = Permission::find($id);
        $provinces=Province::all();
        return view('user::'.env("ADMIN_THEME").'.permission.edit')->with('permission', $permission)->with('provinces',$provinces);
    }

    public function Update(Request $request)
    {

        $data = $request->all();
        $valid = Validator::make($data, [
            'name' => 'required|max:255|unique:usermodule_permissions,name,' . $data['id'],
            'display_name' => 'required|max:255|unique:usermodule_permissions,display_name,' . $data['id']]);

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

    public function Destroy(Request $request)
    {
        $id = $request->input('id');
        $permission = Permission::find($id);
        $permission->delete();

        return Redirect::back()->withErrors(trans('user::messages.done'));
    }

}
