<?php
    namespace App\Services\Interfaces;
    
    interface RateServiceInterface
    {
        function list(int $perPage, int $page, string $text);
        function get(int $id);
        function insert(array $rate);
        function update(array $rate, int $id);
        function delete(int $id);        
    }
?>