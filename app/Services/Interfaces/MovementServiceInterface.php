<?php
    namespace App\Services\Interfaces;
    
    interface MovementServiceInterface
    {
        function get(string $start_date, string $final_date); 
        function generate(array $data);  
    }
?>