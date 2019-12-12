<?php

namespace App\StockMovements;
    
interface StockMovementInterface
{

    public function prepareToProcess();
    
    public function process();

}