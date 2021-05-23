<?php
    namespace App\Services\Interfaces;
    
    interface UserServiceInterface
    {
        function listUser();
        function getUser(int $user);
        function insertUser(array $user);
        function updateUser(array $user, int $id);
        function deleteUser(int $id);
    }
?>