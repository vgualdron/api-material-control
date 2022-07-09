<?php
    namespace App\Services\Interfaces;
    
    interface MaterialSettlementServiceInterface
    {
        function list(string $type, string $start_date, string $final_date, int $third, int $material, string $material_type);
        function settle(Array $data);
    }
?>