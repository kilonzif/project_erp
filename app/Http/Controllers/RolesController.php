<?php

namespace App\Http\Controllers;

use App\Classes\ToastNotification;
use App\Permission;
use App\Role;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RolesController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('ability:webmaster|super-admin,add-roles');
    }

    public function index(){

        if (Auth::user()->hasRole('webmaster')){
            $roles = Role::all();
        }else{
            $roles = Role::where('name','<>', 'webmaster')->get();
        }

        $permissions = Permission::all();
        return view('roles.index', compact('roles','permissions'));
    }

    public function emptyForm(){
        $permissions = Permission::all();
        $theView = view('roles.empty_form', compact('permissions'));
        return $theView;
    }

    public function create(Request $request){

        $request->role_name = str_slug(strtolower($request->role_name));

        $this->validate($request,[
            'role_name' => 'required|string|min:2|unique:roles,name',
            'display_name' => 'nullable|string|min:2',
            'role_desp' => 'nullable|string|min:3',
            'permissions' => 'required|array|min:1',
            'permissions.*' => 'required|numeric|min:1'
        ]);

        try{
            $owner = new Role();
            $owner->name         = $request->role_name;
            $owner->display_name = $request->display_name;
            $owner->description  = $request->role_desp;
            $owner->save();

            $owner->attachPermissions($request->permissions);
            notify(new ToastNotification('Successful!', 'Role Added!', 'success'));

        }catch(\Illuminate\Database\QueryException $ex){

            notify(new ToastNotification('Error!', 'Role already exist or check enter appropriate data.!', 'warning'));
            return back();
        }

        return back();
    }

    public function edit(Request $request){
        $this->validate($request,[
            'name' => 'required|string|min:3'
        ]);
        $fixed_roles = ['webmaster','super-admin','admin','ace-officer','manager'];
        $role = Role::where('name','=',$request->name)->first();
        $role_perms = $role->permissions;
        $role_permissions = array();

        foreach ($role_perms as $role_perm){
            $role_permissions[]=$role_perm->id;
        }
        $permissions = Permission::all();

        $theView = view('roles.edit', compact('role','permissions','role_permissions','fixed_roles'))->render();
        return response()->json(['theView'=>$theView]);
    }

    public function update(Request $request)
    {
        $request->role_name = str_slug(strtolower($request->role_name));
        $fixed_roles = ['webmaster','super-admin','admin','ace-officer','manager'];
        $thisRole = Role::find($request->id);
        if (in_array($thisRole->name, $fixed_roles)){
            $request->role_name = $thisRole->name;
        }

        $this->validate($request,[
            'id' => 'required|numeric|min:1',
            'role_name' => 'required|string|min:3|unique:roles,name,'.$request->id,
            'display_name' => 'nullable|string|min:3',
            'role_desp' => 'nullable|string|min:3',
            'permissions' => 'required|array|min:1',
            'permissions.*' => 'required|numeric|min:1'
        ]);

        try {
            $owner = Role::find($request->id);
            $owner->name = str_slug(strtolower($request->role_name));
            $owner->display_name = $request->display_name;
            $owner->description = $request->role_desp;
            $owner->save();

            $owner->syncPermissions($request->permissions);
            notify(new ToastNotification('Successful!', 'Role Updated!', 'success'));
            return back();
        }catch (QueryException $exception){
            notify(new ToastNotification('Error!', 'Role already exist or check enter appropriate data.!', 'warning'));
            return back();
        }
    }
}
