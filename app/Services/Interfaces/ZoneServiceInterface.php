<?php
    namespace App\Services\Interfaces;
    
    interface ZoneServiceInterface
    {
        function list(int $perPage, int $page, string $text, int $zone);
        function get(int $id);
        function insert(array $zone);
        function update(array $zone, int $id);
        function delete(int $id);        
    }
?>