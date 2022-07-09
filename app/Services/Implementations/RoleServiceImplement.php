<?php
    namespace App\Services\Implementations;
    use App\Services\Interfaces\RoleServiceInterface;
    use App\Models\Role;
    use App\Models\Permission;
    use Spatie\Permission\Models\Role as RoleSpatie;
    
    class RoleServiceImplement implements RoleServiceInterface{
        
        private $model;
        private $modelRole;
        private $modelPermission;

        function __construct(){
            $this->model = new Role;
            $this->modelRole = new RoleSpatie;
            $this->modelPermission = new Permission;
        }    

        function list(int $perPage, int $page, string $text){

           $mainQuery = $this->model->where(function ($query) use ($text){
                                $query->where('name', 'like', '%'.$text.'%');
                                                    }
                            )
                        ->orderBy('name')
                        ->paginate($perPage, ['id', 'name'], 'page', $page);

            return $mainQuery;
        }

        function get(int $id){
            $role = $this->model->where('id', $id)->first();
            $mainRole = $this->modelRole->where('id', $id)->first();
            $permissions = $mainRole ->permissions->pluck('name');
            $array = array();
            for($i=0; $i<count($permissions); $i++){
                $array[$i] = $permissions[$i];
            }
            $role['permissions'] = $array;
            return $role;
        }

        function insert(array $role){            
            $roleModel = $this->model->create([
                        'name' =>  $role['name'],
                        'guard_name' =>  $role['guard_name']
                    ]);
            /* get general permissions to add to user permissions */
            $generalPermissions = $this->modelPermission->where('general', 1)->get();
            $array = array();
            foreach($generalPermissions as $perm){
                $array[] = $perm->name;
            }
            $array = array_merge($role['permissions'], $array);
            $mainRole = $this->modelRole->where('id', $roleModel->id)->first();
            $mainRole->givePermissionTo($array);
            return $roleModel;
        }

        function update(array $role, int $id){  
            $this->model->where('id', $id)->first()
            ->fill([    
                    'name' =>  $role['name'],
                    'guard_name' =>  $role['guard_name']
                ])->save();
            $roleModel = $this->model->where('id', $id)->first();
            $mainRole = $this->modelRole->where('id', $roleModel->id)->first();
            $mainRole->permissions()->detach();
            $generalPermissions = $this->modelPermission->where('general', 1)->get();
            $array = array();
            foreach($generalPermissions as $perm){
                $array[] = $perm->name;
            }
            $array = array_merge($role['permissions'], $array);
            $mainRole->givePermissionTo($array);
            return $roleModel;
        }

        function delete(int $id){         
            $role = $this->model->find($id);                    
            $role->delete();
        }
    }
?>