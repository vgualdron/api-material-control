<?php
    namespace App\Services\Interfaces;
    
    interface ReportServiceInterface
    {
        function movementsReport(string $movement, string $start_date, string $final_date, int $origin_yard_supplier, int $destiny_yard_customer, int $material);
        function yardStockReport(string $date);
        function completeTransfersReport(string $start_date, string $final_date, int $origin_yard, int $destiny_yard);
        function uncompleteTransfersReport(string $start_date, string $final_date, int $origin_yard, int $destiny_yard);
        function unbilledPurchasesReport(string $start_date, string $final_date, int $supplier, int $material);
        function unbilledSalesReport(string $start_date, string $final_date, int $customer, int $material);
        function unbilledFreightReport(string $start_date, string $final_date, int $conveyor_company, int $material);
    }
?>