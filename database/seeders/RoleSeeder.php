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
        Permission::create(['name' => 'user.get'])->syncRoles([$role1]);
        Permission::create(['name' => 'user.updateProfile'])->syncRoles([$role1, $role2]);
        /* zone permissions */
        Permission::create(['name' => 'zone.insert'])->syncRoles([$role1]);
        Permission::create(['name' => 'zone.list'])->syncRoles([$role1]); 
        Permission::create(['name' => 'zone.update'])->syncRoles([$role1]);
        Permission::create(['name' => 'zone.delete'])->syncRoles([$role1]);
        Permission::create(['name' => 'zone.get'])->syncRoles([$role1]);
        /* yard permissions */
        Permission::create(['name' => 'yard.insert'])->syncRoles([$role1]);
        Permission::create(['name' => 'yard.list'])->syncRoles([$role1]); 
        Permission::create(['name' => 'yard.update'])->syncRoles([$role1]);
        Permission::create(['name' => 'yard.delete'])->syncRoles([$role1]);
        Permission::create(['name' => 'yard.get'])->syncRoles([$role1]);
       /* tiquet permissions */
        Permission::create(['name' => 'tiquet.insert'])->syncRoles([$role1]);
        Permission::create(['name' => 'tiquet.list'])->syncRoles([$role1]); 
        Permission::create(['name' => 'tiquet.update'])->syncRoles([$role1]);
        Permission::create(['name' => 'tiquet.delete'])->syncRoles([$role1]);
    }
}
