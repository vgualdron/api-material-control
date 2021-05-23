<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role1 = Role::create(['name' => 'Admin']);
        $role2 = Role::create(['name' => 'Patiero']);
        /* usser permissions */
        Permission::create(['name' => 'user.insert'])->syncRoles([$role1]);
        Permission::create(['name' => 'user.list'])->syncRoles([$role1]); 
        Permission::create(['name' => 'user.update'])->syncRoles([$role1]);
        Permission::create(['name' => 'user.delete'])->syncRoles([$role1]);
       /* tiquet permissions */
        Permission::create(['name' => 'tiquet.insert'])->syncRoles([$role1]);
        Permission::create(['name' => 'tiquet.list'])->syncRoles([$role1]); 
        Permission::create(['name' => 'tiquet.update'])->syncRoles([$role1]);
        Permission::create(['name' => 'tiquet.delete'])->syncRoles([$role1]);
    }
}
