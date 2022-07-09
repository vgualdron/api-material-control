<?php
    namespace App\Services\Interfaces;
    
    interface SynchronizeServiceInterface
    {
        function fromServer();
        function toServer(array $objects);
    }
?>