<?php

namespace app\modules\orderList\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class Services
 * @package app\models
 */

class Services extends ActiveRecord
{
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Orders::className(), ['service_id' => 'id']);
    }

    /**
     * @return mixed
     */
    public function getOrdersCount()
    {
        return $this->ordersAggregation[0]['cnt'];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrdersAggregation(): ActiveQuery
    {
        return $this->getOrders()
            ->select(['count(*) as cnt'])
            ->groupBy('service_id')
            ->asArray(true);
    }
}