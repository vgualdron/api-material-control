<?php
    namespace App\Services\Interfaces;
    
    interface YardServiceInterface
    {
        function list(int $perPage, int $page, string $text, int $yard, int $excludedYard);
        function get(int $id);
        function insert(array $yard);
        function update(array $yard, int $id);
        function delete(int $id);        
    }
?>