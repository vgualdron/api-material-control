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
        Permission::create(['name' => 'user.insert', 'display_name' => 'Insertar Usuario'])->syncRoles([$role1]);
        Permission::create(['name' => 'user.list', 'display_name' => 'Listar Usuario'])->syncRoles([$role1]); 
        Permission::create(['name' => 'user.update', 'display_name' => 'Actualizar Usuario'])->syncRoles([$role1]);
        Permission::create(['name' => 'user.delete', 'display_name' => 'Eliminar Usuarios'])->syncRoles([$role1]);
        Permission::create(['name' => 'user.get', 'display_name' => 'Buscar Usuario'])->syncRoles([$role1]);
        Permission::create(['name' => 'user.changePassword', 'is_function' => 0, 'general' => 1,  'display_name' => 'Cambiar Contraseña'])->syncRoles([$role1, $role2]);
        /* zone permissions */
        Permission::create(['name' => 'zone.insert', 'display_name' => 'Insertar Zona'])->syncRoles([$role1]);
        Permission::create(['name' => 'zone.list', 'display_name' => 'Listar Zonas'])->syncRoles([$role1]); 
        Permission::create(['name' => 'zone.update', 'display_name' => 'Actualizar Zona'])->syncRoles([$role1]);
        Permission::create(['name' => 'zone.delete', 'display_name' => 'Eliminar Zona'])->syncRoles([$role1]);
        Permission::create(['name' => 'zone.get', 'display_name' => 'Buscar Zona'])->syncRoles([$role1]);
        /* yard permissions */
        Permission::create(['name' => 'yard.insert', 'display_name' => 'Insertar Patio'])->syncRoles([$role1]);
        Permission::create(['name' => 'yard.list', 'display_name' => 'Listar Patios'])->syncRoles([$role1]); 
        Permission::create(['name' => 'yard.update', 'display_name' => 'Actualizar Patio'])->syncRoles([$role1]);
        Permission::create(['name' => 'yard.delete', 'display_name' => 'Eliminar Patio'])->syncRoles([$role1]);
        Permission::create(['name' => 'yard.get', 'display_name' => 'Consultar Patio'])->syncRoles([$role1]);
        /* tiquet permissions */
        Permission::create(['name' => 'tiquet.insert', 'offline' => 1, 'display_name' => 'Insertar Tiquet'])->syncRoles([$role1]);
        Permission::create(['name' => 'tiquet.list', 'offline' => 1, 'display_name' => 'Listar Tiquets'])->syncRoles([$role1]); 
        Permission::create(['name' => 'tiquet.update', 'offline' => 1, 'display_name' => 'Actualizar Tiquet'])->syncRoles([$role1]);
        Permission::create(['name' => 'tiquet.delete', 'offline' => 1, 'display_name' => 'Eliminar Tiquet'])->syncRoles([$role1]);
        Permission::create(['name' => 'tiquet.get', 'offline' => 1, 'display_name' => 'Consultar Tiquet'])->syncRoles([$role1]);
        /* material permissions */
        Permission::create(['name' => 'material.insert', 'display_name' => 'Insertar Material'])->syncRoles([$role1]);
        Permission::create(['name' => 'material.list', 'display_name' => 'Listar Materiales'])->syncRoles([$role1]);
        Permission::create(['name' => 'material.update', 'display_name' => 'Actualizar Material'])->syncRoles([$role1]);
        Permission::create(['name' => 'material.delete', 'display_name' => 'Eliminar Material'])->syncRoles([$role1]);
        Permission::create(['name' => 'material.get', 'display_name' => 'Buscar Material'])->syncRoles([$role1]);
        /* role permissions */
        Permission::create(['name' => 'role.insert', 'display_name' => 'Insertar Rol'])->syncRoles([$role1]);
        Permission::create(['name' => 'role.list', 'display_name' => 'Listar Rol'])->syncRoles([$role1]);
        Permission::create(['name' => 'role.update', 'display_name' => 'Actualizar Rol'])->syncRoles([$role1]);
        Permission::create(['name' => 'role.delete', 'display_name' => 'Eliminar Rol'])->syncRoles([$role1]);
        Permission::create(['name' => 'role.get', 'display_name' => 'Buscar Rol'])->syncRoles([$role1]);
        /* synchronize permissions */
        Permission::create(['name' => 'synchronize.fromServer', 'is_function' => 0, 'general' => 1, 'display_name' => 'Sincronizar Desde el Servidor'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'synchronize.toServer', 'is_function' => 0, 'general' => 1, 'display_name' => 'Sincronizar Hacia el Servidor'])->syncRoles([$role1, $role2]);
        /* synchronize permissions */
        Permission::create(['name' => 'session.get', 'is_function' => 0, 'general' => 1, 'display_name' => 'Obtener Info Sesion'])->syncRoles([$role1, $role2]);
    }
}
