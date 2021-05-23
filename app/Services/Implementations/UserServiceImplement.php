<?php
    namespace App\Services\Implementations;
    use App\Services\Interfaces\UserServiceInterface;
    use App\Models\User;
    use Illuminate\Support\Facades\Hash;
    
    class UserServiceImplement implements UserServiceInterface{
        
        private $model;

        function __construct(){
            $this->model = new User;
        }    

        function listUser(){
            return $this->model->get();          
        }

        function getUser(int $user){
            return $this->model->get();
        }

        function insertUser(array $user){                        
            $user['password'] = Hash::make($user['password']);            
            $model = $this->model->create($user);
            return $model;          
        }

        function updateUser(array $user, int $id){  
            $this->model->findOrFail($id);       
            $user['password'] = Hash::make($user['password']); 
            $model = $this->model->where('id', $id)->first();            
            $this->model->where('id', $id)->first()
            ->fill($user)->save();            
            return $model;
        }

        function deleteUser(int $id){ 
            $this->model->findOrFail($id);            
            $user->delete();            
        }
    }
?>