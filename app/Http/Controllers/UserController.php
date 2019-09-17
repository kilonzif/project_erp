<?php

namespace App\Http\Controllers;

use App\Ace;
use App\Classes\SystemMail;
use App\Classes\ToastNotification;
use App\Institution;
use App\Permission;
use App\Role;
use App\SystemOption;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth')->except('verify_user');
    }

    /**
     * Users List
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){
        if (Auth::user()->hasRole('webmaster')){
            $roles = Role::orderBy('name', 'ASC')->get();
            $users = User::orderBy('name', 'ASC')->get();
        }else{
            $roles = Role::where('name', '<>', 'webmaster')->get();
            $W_users = User::whereRoleIs('webmaster')->pluck('id');
            $users = User::whereNotIn('id', $W_users)->orderBy('name', 'ASC')->get();
        }

        //The ACES represent GROUPS
        $aces = Ace::where('active','=',1)->orderBy('name', 'ASC')->get();
        $institutions = Institution::where('university','=',0)->where('active','=',1)->orderBy('name', 'ASC')->get();

        $institution_name=Institution::where('id','=',$institutions)->orderBy('name', 'ASC')->pluck('name');
//        dd($institution_name);
        return view('users.index', compact('users','roles','institutions','aces','institution_name'));
    }


    /**
     * User create view
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(){
        $users = User::all();
        if (Auth::user()->hasRole('webmaster')){
            $roles = Role::orderBy('name', 'ASC')->get();
        }else{
            $roles = Role::where('name', '<>', 'webmaster')->orderBy('name', 'ASC')->get();
        }

        //The ACES represent GROUPS
        $aces = Ace::where('active','=',1)->orderBy('name', 'ASC')->get();
        $institutions = Institution::where('university','=',0)->where('active','=',1)->orderBy('name', 'ASC')->get();
        return view('users.create', compact('users','roles','institutions','aces'));
    }

    /**
     * Auth Profile
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function myprofile()
    {
        $id = Auth::id();
        $user = User::find($id);
        $roles = Role::orderBy('name', 'ASC')->get();
        $permissions = Permission::orderBy('name', 'ASC')->get();
//        $aces = Ace::get();
        $user_perms = $user->permissions;
        $user_permissions = array();

        foreach ($user_perms as $user_perm){
            $user_permissions[]=$user_perm->id;
        }
        $user_rs = $user->roles;
        $user_roles = array();

        foreach ($user_rs as $user_r){
            $user_roles[]=$user_r->id;
        }
//        $institutions = Institution::where('university','=',0)->get();
        return view('users.profile', compact('user','permissions','user_permissions','roles','user_roles'));
    }

    /**
     * User Profile
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function profile($id){
        $id = Crypt::decrypt($id);
        $user = User::find($id);
        $roles = Role::get();
        $permissions = Permission::get();
//        $aces = Ace::get();
        $user_perms = $user->permissions;
        $user_permissions = array();

        foreach ($user_perms as $user_perm){
            $user_permissions[]=$user_perm->id;
        }
        $user_rs = $user->roles;
        $user_roles = array();

        foreach ($user_rs as $user_r){
            $user_roles[]=$user_r->id;
        }
//        $institutions = Institution::where('university','=',0)->get();
        return view('users.profile', compact('user','permissions','user_permissions','roles','user_roles'));
    }

    /**
     * Save new a user
     *
     * @return \Illuminate\Http\Response
     */
    public function save_user(Request $request){

        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'ace' => 'nullable|numeric|min:1',
            'role' => 'required|numeric|min:1',
            'phone' => 'required|string|numeric|min:1',
            'institution' => 'nullable|integer|min:1'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->email),
            'phone' => $request->phone,
            'status' => 1,
            'institution' => $request->institution,
            'ace' => $request->ace,
            'remember_token' => substr(Crypt::encrypt($request->email),0,30),
        ]);
        $email = SystemOption::where('option_name', '=', 'app_email')->pluck('display_name')->first();
        if (!isset($email)){
            $email = "no-reply@aau@org";
        }

        $user->attachRole($request->role);
        $send_mail = new SystemMail();
        try{
            $send_mail->to($user->email)
                ->from(strtolower($email))
                ->subject('Account Confirmation & Password')
                ->markdown('mail.email-confirmation',['user'=>$user])
                ->send();
        }catch (\Throwable $exception){

        }
        notify(new ToastNotification('Successful!', 'New user added!', 'success'));
        return back();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function edit_user_view(Request $request){
        $id = Crypt::decrypt($request->id);
        $user = User::find($id);
        if (Auth::user()->hasRole('webmaster')){
            $roles = Role::orderBy('name', 'ASC')->get();
        }else{
            $roles = Role::where('name', '<>', 'webmaster')->orderBy('name', 'ASC')->get();
        }

        //The ACES represent GROUPS
        $aces = Ace::where('active','=',1)->orderBy('name', 'ASC')->get();
        $institutions = Institution::where('university','=',0)->where('active','=',1)->orderBy('name', 'ASC')->get();
        $view = view('users.edit-view', compact('user','roles','institutions','aces'))->render();
        return response()->json(['theView'=>$view]);
    }


    /**
     * Update a user
     *
     * @return \Illuminate\Http\Response
     */
    public function update_user(Request $request){

        $id = Crypt::decrypt($request->id);
        $this->validate($request, [
            'id' => 'required|string|min:100',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$id,
            'ace' => 'nullable|numeric|min:1',
            'role' => 'required|numeric|min:1',
            'phone' => 'required|string|numeric|min:1',
            'institution' => 'nullable|integer|min:1'
        ]);

        $user = User::find($id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'institution' => $request->institution,
            'ace' => $request->ace,
        ]);
        $roles = array();
        $roles[] = $request->role;
        $user->syncRoles($roles);
        notify(new ToastNotification('Successful!', 'User Updated!', 'success'));
        return back();
    }

    /**
     * Edit user profile
     *
     * @return \Illuminate\Http\Response
     */
    public function edit_user(Request $request,$id){

        $user = Crypt::decrypt($id);
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user,
            'phone' => 'required|string|numeric|digits_between:10,20'
        ]);

        User::find($user)->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);
//        $user->attachRole($request->role);
        notify(new ToastNotification('Successful!', 'Profile Updated!', 'success'));
        return back();
    }

    /**
     * Saves the new password.
     *
     * @return \Illuminate\Http\Response
     */
    public function save_password(Request $request,$id)
    {
        $this->validate($request,[
            'password' => 'required|string|min:6|confirmed'
        ]);
//        return $request->all();
        $user = User::find(Crypt::decrypt($id));
        $user->password = Hash::make($request->password);
        $user->save();
        notify(new ToastNotification('Successful!', 'Password Changed!', 'success'));
        return back();
    }

    /**
     * Activation of User Account.
     *
     * @return \Illuminate\Http\Response
     */
    public function remove_user(Request $request, $id){
        $user = User::find(Crypt::decrypt($request->user));

//        dd($user->reports->count());
        if ($user->reports->count() >= 1){
            notify(new ToastNotification('Sorry!', 'User cannot be deleted! This user has a report submitted.', 'warning'));
        }else{
            $user->delete();
            notify(new ToastNotification('Successful!', 'User Deleted!', 'success'));
        }

        return back();
    }

    /**
     * Activation of User Account.
     *
     * @return \Illuminate\Http\Response
     */
    public function status_user(Request $request, $id){
        $user = User::find(Crypt::decrypt($request->user));

        if ($user->status == 1){
            $user->status = 0;
            $user->save();
            notify(new ToastNotification('Successful!', 'User Deactivated!', 'success'));
        }else{
            $user->status = 1;
            $user->email_verified_at = date('Y-m-d H:i:s');
            $user->save();
            notify(new ToastNotification('Successful!', 'User Activated!', 'success'));
        }

        return back();
    }

    /**
     * Activation of User Account.
     *
     * @return \Illuminate\Http\Response
     */
    public function verify_user($token){

        $user = User::where('remember_token','=',$token)->first();

        if ($user){
            $user->status = 1;
            $user->remember_token = "";
            $user->email_verified_at = date('Y-m-d H:i:s');
            $user->save();
            notify(new ToastNotification('Successful!', 'Your account has been activated. Please login', 'success'));
        }else{
            notify(new ToastNotification('Sorry!', 'Invalid token for activation', 'warning'));
        }
        return redirect()->route('home');
    }

    /**
     * Save User Permissions
     */
    public function permissions_save(Request $request, $id)
    {
        $this->validate($request,[
            'permissions' => 'nullable|array|min:1',
            'permissions.*' => 'nullable|numeric|min:1',
        ]);

        $user_id = Crypt::decrypt($id);
        $user = User::find($user_id);
        if (is_null($request->permissions)){
//            dd($request->permissions);
            DB::table('permission_user')->where('user_id','=', $user_id)->delete();
        }else{
            $user->syncPermissions($request->permissions);
        }
        notify(new ToastNotification('Successful!', 'Permissions updated!', 'success'));
        return back();
    }

    /**
     * Set User Roles
     */
    public function roles_save(Request $request,$id)
    {
        $this->validate($request,[
            'roles' => 'nullable|array|min:1',
            'roles.*' => 'nullable|numeric|min:1',
        ]);

        $user_id = Crypt::decrypt($id);
        $user = User::find($user_id);
        if (is_null($request->roles)){
//            dd($request->permissions);
            DB::table('role_user')->where('user_id','=', $user_id)->delete();
        }else{
            $user->syncRoles($request->roles);
        }
        notify(new ToastNotification('Successful!', 'Roles updated!', 'success'));
        return back();
    }
}
