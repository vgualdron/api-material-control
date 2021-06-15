<?php
    namespace App\Services\Implementations;   
    use App\Services\Interfaces\SynchronizeServiceInterface;
    use App\Models\Material;
    use App\Models\ModelHasRoles;
    use App\Models\AuthTokens;
    use App\Models\Permission;
    use App\Models\Role;
    use App\Models\RolePermission;
    use App\Models\Zone;
    use App\Models\Yard;
    use App\Models\User;
    
    class SynchronizeServiceImplement implements SynchronizeServiceInterface{
        
        private $material;
        private $modelHasRoles;
        private $authTokens;
        private $permission;
        private $role;
        private $rolePermission;
        private $zone;
        private $yard;
        private $user;
        private $userRol;

        function __construct(){            
            $this->material = new Material;
            $this->modelHasRoles = new ModelHasRoles;
            $this->authTokens = new AuthTokens;
            $this->permission = new Permission;
            $this->role = new Role;
            $this->rolePermission = new RolePermission;
            $this->zone = new Zone;
            $this->yard = new Yard;
            $this->user = new User;
            $this->userRol = new ModelHasRoles;
        } 

        function fromServer(){       
            $synchro["material"] =  $this->material->get();
            $synchro["authToken"] =  $this->authTokens->get();
            $synchro["permission"] =  $this->permission->get();
            $synchro["role"] =  $this->role->get();
            $synchro["rolePermission"] =  $this->rolePermission->get();
            $synchro["zone"] =  $this->zone->get();
            $synchro["yard"] =  $this->yard->get();
            $synchro["user"] =  $this->user->get();
            $synchro["userRol"] =  $this->userRol->get();
            return $synchro;
        }
    }
?>