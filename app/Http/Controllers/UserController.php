<?php

namespace App\Http\Controllers;

use App\Ace;
use App\Classes\CommonFunctions;
use App\Classes\SystemMail;
use App\Classes\ToastNotification;
use App\Contacts;
use App\Currency;
use App\IndicatorOne;
use App\Institution;
use App\Permission;
use App\Position;
use App\Role;
use App\SectoralBoard;
use App\SystemOption;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

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
        $aces = Ace::where('active','=',1)->orderBy('acronym', 'ASC')->get();
        $institutions = Institution::where('active','=',1)->orderBy('name', 'ASC')->get();
//        dd($institutions);
        return view('users.index', compact('users','roles','institutions','aces'));
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
        $aces = Ace::where('active','=',1)->orderBy('acronym', 'ASC')->get();
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
            'name' => 'required|string|max:255|regex:/^[\pL\s\-]+$/u',
            'email' => 'required|string|email|max:255|unique:users',
            'ace' => 'nullable|numeric|min:1',
            'role' => 'required|numeric|min:1',
            'phone' => 'required|string|numeric|min:1',
            'institution' => 'nullable|integer|min:1'
        ]);
            $user = new User();
            $user->name = $request->name;
             $user->email = $request->email;
                $user->password = Hash::make($request->email);
                $user->phone = $request->phone;
                $user->status = 1;
                $user->institution = $request->institution;
                $user->ace = $request->ace;
                $user->remember_token = substr(Crypt::encrypt($request->email), 0, 30);



           $saved= $user->save();
            $user->attachRole($request->role);
            if($saved) {
                $user_email = $request->email;
                Mail::send('mail.email-confirmation', ['email' => $user_email, 'user' => $user],
                    function ($message) use ($user_email) {
                        $message->to($user_email)
                            ->subject("Ace-Impact [ Account Creation ]");
                    });


            }else{
                notify(new ToastNotification('Error!', 'Failed to add a user!', 'error'));
                return back()->withInput();
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
        $aces = Ace::where('active','=',1)->orderBy('acronym', 'ASC')->get();
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
            'name' => 'required|string|max:255|regex:/^[\pL\s\-]+$/u',
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


    public function getAceProfile()
    {
        if(isset(Auth::user()->name) ){
            $ace_id = Auth::user()->ace;
            $user_id=Auth::user()->id;
        }
        $ace= Ace::find($ace_id);
        $contacts = $this->getContactGroup($ace_id);
//        dd($contacts);
        $positions = Position::orderBy('rank','ASC')->get();

        $currency1 =  Currency::where('id','=',$ace->currency1_id)->orderBy('name', 'ASC')->first();
        $currency2= Currency::where('id','=',$ace->currency2_id)->orderBy('name', 'ASC')->first();

        $indicatorOne = new CommonFunctions();
        $labels = $indicatorOne->getRequirements(null);

        $contact_positions = $indicatorOne->getContactTitles(null);

        $indicator_ones =IndicatorOne::where('ace_id', '=', $ace_id)->get();


//        board members
        $board_members=$this->getSectorialAdvisoryBoardMembers($ace_id);

        return view('aces_profile',compact('ace','contacts','positions','contact_positions','board_members','currency1','currency2','labels','indicator_ones'));
    }
    public function getContactGroup($ace_id){
        $the_ace = Ace::find($ace_id);
        $contacts = DB::table('contacts')->join('ace_contacts', 'ace_contacts.contact_id', '=', 'contacts.id')
            ->rightJoin('positions','positions.id','contacts.position_id')
            ->where('ace_contacts.ace_id','=',$ace_id)
            ->select('contacts.*','positions.position_title')
            ->get();


        return $contacts;
    }




    public function getSectorialAdvisoryBoardMembers($ace_id){
        $members = SectoralBoard::where('ace_id','=',$ace_id)->get();

        return $members;

    }


}
