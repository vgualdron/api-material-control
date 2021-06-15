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
        Permission::create(['name' => 'user.changePassword'])->syncRoles([$role1, $role2]);
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
        Permission::create(['name' => 'tiquet.insert', 'offline' => 1])->syncRoles([$role1]);
        Permission::create(['name' => 'tiquet.list', 'offline' => 1])->syncRoles([$role1]); 
        Permission::create(['name' => 'tiquet.update', 'offline' => 1])->syncRoles([$role1]);
        Permission::create(['name' => 'tiquet.delete', 'offline' => 1])->syncRoles([$role1]);
        Permission::create(['name' => 'tiquet.get'])->syncRoles([$role1]);
        /* material permissions */
        Permission::create(['name' => 'material.insert'])->syncRoles([$role1]);
        Permission::create(['name' => 'material.list'])->syncRoles([$role1]);
        Permission::create(['name' => 'material.update'])->syncRoles([$role1]);
        Permission::create(['name' => 'material.delete'])->syncRoles([$role1]);
        Permission::create(['name' => 'material.get'])->syncRoles([$role1]);
        /* synchronize permissions */
        Permission::create(['name' => 'synchronize.fromServer', 'is_function' => 0])->syncRoles([$role1]);
        Permission::create(['name' => 'synchronize.toServer', 'is_function' => 0])->syncRoles([$role1]);
    }
}
