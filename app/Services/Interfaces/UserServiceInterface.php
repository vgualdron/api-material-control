<?php
    namespace App\Services\Interfaces;
    
    interface UserServiceInterface
    {
        function list();
        function get(int $user);
        function insert(array $user);
        function update(array $user, int $id);
        function delete(int $id);
        function updateProfile(array $user, int $id);
    }
?>