<?php
    namespace App\Services\Implementations;
    use App\Services\Interfaces\PermissionServiceInterface;
    use App\Models\User;
    use App\Models\Permission;
    use Illuminate\Contracts\Auth\Factory as Auth;
    
    class PermissionServiceImplement implements PermissionServiceInterface{
                
        private $model;
        
        function __construct(){
           $this->model = new Permission;
        }    

        function listBySession($user){            
            $permissions = $user->getAllPermissions(); 
            $array = array();
                              
            for($i = 0; $i<count($permissions); $i++){                
                if($permissions[$i]["is_function"]){
                    array_push($array, $permissions[$i]["name"]);                    
                }                
            }             
            sort($array);            
            $permArray = array();
            $genArray = array();
            $objectArr = array();
            for($i=0; $i<count($array); $i++){
                $perm = explode(".", $array[$i])[0];   
                array_push($permArray, explode(".", $array[$i])[1]); 
                
                if($i == count($array)-1 ||  explode(".", $array[$i+1])[0]!=$perm){                   
                    $objectArr['name'] = $perm;
                    $objectArr['permissions'] = $permArray;
                    array_push($genArray, $objectArr);
                    $objectArr = array();
                    $permArray = array();
                }
            }            
            return $genArray;
                       
        }

        function listBySessionGroup($user){            
            $permissions = $user->getAllPermissions(); 
            $array = array();
            for($i = 0; $i<count($permissions); $i++){
                array_push($array, $permissions[$i]["name"]);                   
            }                  
            return $array; 
        }
        
        function listByGroup(){            
            return $this->model->where('general', 0)->get();
        }
    }
?>