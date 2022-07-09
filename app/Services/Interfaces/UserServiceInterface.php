<?php
    namespace App\Services\Interfaces;
        
    interface UserServiceInterface
    {
        function list(int $perPage, int $page, string $text);
        function get(int $user);
        function insert(array $user);
        function update(array $user, int $id);
        function delete(int $id);
        function changePassword(array $user, int $id);
    }
?>