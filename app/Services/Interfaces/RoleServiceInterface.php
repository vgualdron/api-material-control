<?php
    namespace App\Services\Interfaces;
    
    interface RoleServiceInterface
    {
        function list(int $perPage, int $page, string $text);
        function get(int $id);
        function insert(array $role);
        function update(array $role, int $id);
        function delete(int $id);        
    }
?>