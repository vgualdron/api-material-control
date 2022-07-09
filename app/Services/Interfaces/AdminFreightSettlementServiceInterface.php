<?php
    namespace App\Services\Interfaces;
    
    interface AdminFreightSettlementServiceInterface
    {
        function list(int $perPage, int $page, string $text, int $settlement);
        function get(int $id);
        function update(array $data, int $id);
    }
?>