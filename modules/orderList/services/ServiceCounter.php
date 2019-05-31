<?php

namespace app\modules\orderList\services;

use app\modules\orderList\models\Orders;

/**
 * Class ServiceCounter
 * @package app\modules\orderList\services
 */
class ServiceCounter
{
    /**
     * @return string
     * @throws \yii\db\Exception
     */
    public static function countTotalServices() : string
    {
        $serviceTotal = (new Orders())->getTotalCount();
        foreach ($serviceTotal as $value) {
            $serviceTotal = ($value['cnt']);
        }
        return $serviceTotal;
    }
}
