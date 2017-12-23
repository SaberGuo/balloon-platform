<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\User;
use App\Role;
use App\Permission;


class MakeAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'make admin';

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
        //
        $super = Role::updateOrCreate(['name' => 'super'], ['display_name' => '系统管理员']);


        // permissions
        $sys_w = Permission::updateOrCreate(['name' => 'sys_w'], ['display_name' => '系统写']);
        $sys_r = Permission::updateOrCreate(['name' => 'sys_r'], ['display_name' => '系统读']);

        $super->permissions()->sync([$sys_r->id, $sys_w->id]);
        $user = User::updateOrCreate([
            'name' => 'admin',
            'email' => 'admin@dgbuaa.net',
            'password' => bcrypt('password'),
        ]);
        $user->roles()->sync([$super->id]);


    }
}
