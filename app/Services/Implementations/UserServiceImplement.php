<?php
    namespace App\Services\Implementations;
    use App\Services\Interfaces\UserServiceInterface;
    use App\Models\User;
    use Spatie\Permission\Models\Role;
    use Illuminate\Support\Facades\Hash;
    
    class UserServiceImplement implements UserServiceInterface{
        
        private $model;
        private $role;

        function __construct(){
            $this->model = new User;
            $this->role = new Role;
        }    

        function list(){
            return $this->model->get();          
        }

        function get(int $id){
            $user = $this->model->where('id', $id)->first();  
            $roles = $this->role->get();
            $userRole = $user->getRoleNames();           
            $arrayRole;
            for($i=0; $i<count($roles); $i++){
                $arrayRole[$i]['id'] = $roles[$i]['id'];
                $arrayRole[$i]['name'] = $roles[$i]['name'];
                $arrayRole[$i]['value'] = $roles[$i]['name'];                
                $arrayRole[$i]['active'] = $userRole->search($roles[$i]['name']) === false  ? 0 : 1;
            }            
            $user['role'] = $arrayRole;
            return  $user;
        }

        function insert(array $user){                        
            $user['password'] = Hash::make($user['password']);            
            $model = $this->model->create([
                        'name' =>  $user['name'],
                        'document_number' =>  $user['document_number'],
                        'phone' =>  $user['phone'],
                        'password' =>  $user['password']
                    ]);
            $model->assignRole($user['role']);
            return $model;          
        }

        function update(array $user, int $id){    
            $arrayData = [
                'name' =>  $user['name'],
                'document_number' =>  $user['document_number'],
                'phone' =>  $user['phone']
            ];
            if(!empty($user['password'])){
                $arrayData['password'] = $user['password'] = Hash::make($user['password']);
            }
            $this->model->where('id', $id)->first()
            ->fill($arrayData)->save();  
            $model = $this->model->where('id', $id)->first();
            $model->roles()->detach();
            $model->assignRole($user['role']);
            return $model;
        }

        function updateProfile(array $user, int $id){  
            $user['password'] = Hash::make($user['password']);
            $this->model->findOrFail($id);            
            $this->model->where('id', $id)->first()
            ->fill($user)->save();  
            $model = $this->model->where('id', $id)->first();            
            return $model;
        }

        function delete(int $id){         
            $user = $this->model->find($id);                    
            $user->delete();
        }
    }
?>