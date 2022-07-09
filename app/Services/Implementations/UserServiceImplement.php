<?php
    namespace App\Services\Implementations;
    use App\Services\Interfaces\UserServiceInterface;
    use App\Models\User;
    use App\Models\Role;
    use Illuminate\Support\Facades\Hash;
    
    class UserServiceImplement implements UserServiceInterface{
        
        private $model;

        function __construct(){
            $this->model = new User;
        }    

        function list(int $perPage, int $page, string $text){ 
            
            $mainQuery = $this->model->where(function ($query) use ($text){
                $query->where('name', 'like', '%'.$text.'%')
                    ->orWhere('document_number', 'like', '%'.$text.'%')
                    ->orWhere('phone', 'like', '%'.$text.'%');
                    })
            ->orderBy('document_number')
            ->paginate($perPage, ['*'], 'page', $page);
       
            return $mainQuery;
        }

        function get(int $id){
            $user = $this->model->where('id', $id)->first();
            $userRole = $user->getRoleNames(); 
            $user['role'] = $userRole;
            return  $user;
        }

        function insert(array $user){                        
            $user['password'] = Hash::make($user['password']);            
            $model = $this->model->create([
                        'name' =>  $user['name'],
                        'document_number' =>  $user['document_number'],
                        'phone' =>  $user['phone'],
                        'yard' =>  !empty(trim($user['yard'])) ? $user['yard'] : null,
                        'password' =>  $user['password']
                    ]);
            $model->assignRole($user['role']);
            return $model;          
        }

        function update(array $user, int $id){    
            $arrayData = [
                'name' =>  $user['name'],
                'document_number' =>  $user['document_number'],
                'phone' =>  $user['phone'],
                'yard' =>  $user['yard']
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

        function changePassword(array $user, int $id){  
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