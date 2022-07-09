<?php
    namespace App\Services\Interfaces;
    
    interface FreightSettlementServiceInterface
    {
        function list(string $start_date, string $final_date, int $conveyor_company);
        function settle(Array $data);
    }
?>