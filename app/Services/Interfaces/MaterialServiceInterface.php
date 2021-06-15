<?php
    namespace App\Services\Interfaces;
    
    interface MaterialServiceInterface
    {
        function list();
        function get(int $id);
        function insert(array $zone);
        function update(array $zone, int $id);
        function delete(int $id);        
    }
?>