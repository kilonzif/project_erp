<?php

namespace App\Http\Controllers;

use App\Classes\ToastNotification;
use App\Permission;
use Illuminate\Http\Request;

class PermissionsController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:webmaster');
    }

    public function index(){
        $permissions = Permission::all();
        return view('permissions.index', compact('permissions'));
    }

    public function create(Request $request){

        $this->validate($request,[
            'permission_name' => 'required|unique:permissions,name|string|min:3',
            'display_name' => 'nullable|string|min:3',
            'permission_desp' => 'nullable|string|min:3'
        ]);
        try{
            $createPermission = new Permission();
            $createPermission->name         = str_slug(strtolower($request->permission_name));
            $createPermission->display_name = $request->display_name; // optional
            $createPermission->description  = $request->permission_desp; // optional
            $createPermission->save();
            notify(new ToastNotification('Successful!', 'Permission Added!', 'success'));
        }catch(\Illuminate\Database\QueryException $ex){
            notify(new ToastNotification('Error!', 'Permission name already exist or check enter appropriate data.!', 'warning'));
            return back();
        }

        return back();
    }

    public function edit(Request $request)
    {
        $permission = Permission::find($request->id);
        return response()->json($permission);
    }

    public function update(Request $request)
    {
        $this->validate($request,[
            'id' => 'required|numeric|min:1',
            'permission_name' => 'required|unique:permissions,name,'.$request->id.'|string|min:3',
            'display_name' => 'nullable|string|min:3',
            'permission_desp' => 'nullable|string|min:3'
        ]);

        try{
            $updatePermission = Permission::find($request->id);
            $updatePermission->name         = str_slug(strtolower($request->permission_name));
            $updatePermission->display_name = $request->display_name; // optional
            $updatePermission->description  = $request->permission_desp; // optional
            $updatePermission->save();
        }catch(\Illuminate\Database\QueryException $ex){
            notify(new ToastNotification('Error!', 'Permission name already exist or check enter appropriate data.!', 'warning'));
            return back();
        }
        return back();
    }
}
