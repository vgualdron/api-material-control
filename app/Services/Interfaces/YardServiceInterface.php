<?php
    namespace App\Services\Interfaces;
    
    interface YardServiceInterface
    {
        function list();
        function get(int $id);
        function insert(array $yard);
        function update(array $yard, int $id);
        function delete(int $id);        
    }
?>