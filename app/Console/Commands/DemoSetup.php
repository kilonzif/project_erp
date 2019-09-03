<?php

namespace App\Console\Commands;

use App\Permission;
use App\Role;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class DemoSetup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:demo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates initial user account, webmaster role, add-user permission';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //Creates user
        $user = User::updateOrCreate([
            'email' => 'web@master.com'
        ],[
            'name' => 'Web Master',
            'status' => TRUE,
            'password' => Hash::make('webmaster123'),
        ]);

        //Creates add-user permission
        $createPermission = Permission::where('name', '=', 'demo-permission')->first();
        if (!$createPermission){
            $createPermission = new Permission();
            $createPermission->name         = 'demo-permission';
            $createPermission->display_name = 'Add demo permission'; // optional
            $createPermission->description  = 'Allows the assigned user to add demos'; // optional
            $createPermission->save();
        }

        //Creates webmaster role
        $role = Role::where('name', '=', 'webmaster')->first();
        if (!$role) {
            $role = new Role();
            $role->name = 'webmaster';
            $role->display_name = 'Webmaster';
            $role->description = 'Root user';
            $role->save();
        }
        $role->attachPermission($createPermission);

        //Attach the role to the user
        $user->attachRole($role);
    }
}
