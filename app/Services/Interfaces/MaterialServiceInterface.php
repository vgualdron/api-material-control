<?php
    namespace App\Services\Interfaces;
    
    interface MaterialServiceInterface
    {
        function list(int $perPage, int $page, string $text, int $material);
        function get(int $id);
        function insert(array $zone);
        function update(array $zone, int $id);
        function delete(int $id);        
    }
?>