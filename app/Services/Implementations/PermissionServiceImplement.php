<?php
    namespace App\Services\Implementations;
    use App\Services\Interfaces\PermissionServiceInterface;
    use App\Models\User; 
    use App\Models\SessionPermissionModel;
    use Illuminate\Contracts\Auth\Factory as Auth;
    
    class PermissionServiceImplement implements PermissionServiceInterface{
                
        function __construct(){
           
        }    

        /*function listBySession($user){            
            $permissions = $user->getAllPermissions(); 
            $array = array();  
            $permArray = array();                       
            for($i = 0; $i<count($permissions); $i++){                
                if($permissions[$i]["is_function"]){
                    $arrayPerm = explode(".", $permissions[$i]["name"]);  
                    if(!array_key_exists($arrayPerm[0],$array)){
                        $array[$arrayPerm[0]] = $permArray;
                    }
                    array_push($array[$arrayPerm[0]],$arrayPerm[1]);
                }
            } 
            
            return $array;;             
        }*/

        function listBySession($user){
            $permObject = new SessionPermissionModel();
            $permissions = $user->getAllPermissions(); 
            $array = array();  
            $permArray = array();                       
            for($i = 0; $i<count($permissions); $i++){                
                array_push($permObject->$arrayPerm[0],$arrayPerm[1]);
            } 
            
            return $permObject;             
        }

        function listBySessionGroup($user){            
            $permissions = $user->getAllPermissions(); 
            $array = array();  
            $array['permissions'] = array();
            $permArray = array();  
            for($i = 0; $i<count($permissions); $i++){
                array_push($array['permissions'],$permissions[$i]["name"]);                   
            }  
            array_push($permArray, $array);    
            return $permArray; 
        }
    }
?>