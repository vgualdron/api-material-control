<?php
    namespace App\Services\Interfaces;
    
    interface TiquetServiceInterface
    {
        function list(int $perPage, int $page, string $text);
        function get(int $id);
        function delete(int $id);
        function insert(array $tiquet);
        function update(array $tiquet, int $id);
    }
?>