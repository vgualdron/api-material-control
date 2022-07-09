<?php
    namespace App\Services\Interfaces;
    
    interface AdjustmentServiceInterface
    {
        function list(int $perPage, int $page, string $text, int $adjustment);
        function get(int $id);
        function insert(array $adjustment);
        function update(array $adjustment, int $id);
        function delete(int $id);        
    }
?>