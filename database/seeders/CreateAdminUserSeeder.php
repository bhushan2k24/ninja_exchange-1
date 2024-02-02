<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Administrator;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $user = Administrator::create([
        //     'name' => 'ultraexch admin', 
        //     'email' => 'admin@gmail.com',
        //     'password' => bcrypt('123456')
        // ]);
    
        // $role = Role::create(['guard_name'=>'admin','name' => 'admin']);
     
        // $permissions = Permission::pluck('id','id')->all();
   
        // $role->syncPermissions($permissions);
     
        // $user->assignRole([$role->id]);

        $user = Administrator::create([
            'name' => 'broker', 
            'email' => 'broker@gmail.com',
            'password' => bcrypt('123456')
        ]);
    
        $role = Role::create(['guard_name'=>'admin','name' => 'broker']);
     
        $permissions = Permission::pluck('id','id')->all();
   
        $role->syncPermissions($permissions);
     
        $user->assignRole([$role->id]);

        
    }
}
