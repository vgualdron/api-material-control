<?php
    namespace App\Services\Interfaces;
    
    interface ThirdServiceInterface
    {
        function listByType(string $type, string $start_date, string $final_date);
    }
?>