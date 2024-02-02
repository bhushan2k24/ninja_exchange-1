<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'NSEOPT-list',
            'NSEFUT-list',
            'NSEEQT-list',
            'NSECDS-list',
            'MCXFUT-list',
            'GLOBAL_STOCKS-list',
            'GLOBAL_FUTURES-list',
            'FOREX-list',
            'CRYPTO-list',
            'CRICKET-list',
            'CASINO-list',
            'COMEX-list',
            'BINARY-list'

            // 'role-create',
            // 'role-edit',
            // 'role-delete',
            // 'product-list',
            // 'product-create',
            // 'product-edit',
            // 'product-delete'
         ];
      
         foreach ($permissions as $permission) {
              Permission::create(['guard_name' => 'admin','name' => $permission]);
         }
    }
}
