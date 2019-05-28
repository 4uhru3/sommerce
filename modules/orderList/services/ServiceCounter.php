<?php


namespace app\modules\orderList\services;
use app\modules\orderList\models\Orders;

class ServiceCounter
{
    /**
     * @param array $model
     * @return array
     */
    public function countUniqueService(array $model): array
    {
        $uniqueServices = [];

        foreach ($model as $order) {
            if (isset($uniqueServices[$order->services->id])) {
                $uniqueServices[$order->services->id]++;
            } else {
                $uniqueServices[$order->services->id] = 1;
            }
        }
        return $uniqueServices;
    }

    public function countTotalServices() : string
    {
        $serviceTotal = (new Orders())->getTotalCount();
        foreach ($serviceTotal as $value) {
            $serviceTotal = ($value['serviceCount']);
        }

        return $serviceTotal;
    }
}