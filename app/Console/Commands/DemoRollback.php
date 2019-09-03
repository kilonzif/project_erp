<?php

namespace App\Console\Commands;

use App\Permission;
use App\Role;
use App\User;
use Illuminate\Console\Command;

class DemoRollback extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:rollback';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rollback the demo setup';

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
        //Remove all Demo Setup
        User::where('email', '=', 'web@master.com')->delete();
        Permission::where('name', '=', 'demo-permission')->delete();
        Role::where('name', '=', 'webmaster')->delete();
    }
}
