<?php
    namespace App\Services\Interfaces;
    
    interface PermissionServiceInterface
    {
        function listBySession($user);
        function listBySessionGroup($user);
        function listByGroup();
    }
?>