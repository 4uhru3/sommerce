<?php


namespace app\modules\orderList\services;
use app\modules\orderList\models\Orders;

class ServiceCounter
{
    public function countTotalServices() : string
    {
        $serviceTotal = (new Orders())->getTotalCount();
        foreach ($serviceTotal as $value) {
            $serviceTotal = ($value['serviceCount']);
        }

        return $serviceTotal;
    }
}