<?php

namespace App\Http\Controllers;

use App\Permission;
use App\Team;
use Illuminate\Http\Request;

class GroupsController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $groups = Team::all();
        $permissions = Permission::all();
        return view('groups.index', compact('groups','permissions'));
    }

    public function create(Request $request){

        $this->validate($request,[
            'group_name' => 'required|string|min:3',
            'display_name' => 'nullable|string|min:3',
            'group_desp' => 'nullable|string|min:3',
            'permissions' => 'required|array|min:1',
            'permissions.*' => 'required|numeric|min:1'
        ]);
        try{
            $createTeam = new Team();
            $createTeam->name         = str_slug(strtolower($request->group_name));
            $createTeam->display_name = $request->display_name; // optional
            $createTeam->description  = $request->group_desp; // optional
            $createTeam->save();

            $createTeam->attachPermissions($request->permissions);

        }catch(\Illuminate\Database\QueryException $ex){
            return "This group already exist or check enter appropriate data.";
        }

        return back();
    }
}
