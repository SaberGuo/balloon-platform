<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\Role;
use App\Permission;

class AddUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:user {name} {email} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $common = Role::updateOrCreate(['name' => 'common'], ['display_name' => '一般用户']);


        // permissions
        $app_w = Permission::updateOrCreate(['name' => 'app_w'], ['display_name' => '应用写']);
        $app_r = Permission::updateOrCreate(['name' => 'app_r'], ['display_name' => '应用读']);

        $common->permissions()->sync([$app_r->id, $app_w->id]);
        $user = User::updateOrCreate([
            'name' => $this->argument('name'),
            'email' => $this->argument('email'),
            'password' => bcrypt($this->argument('password')),
        ]);
        $user->roles()->sync([$common->id]);

    }
}
