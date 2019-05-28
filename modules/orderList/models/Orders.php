<?php

namespace app\modules\orderList\models;

use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

/**
 * Class Orders
 * @package app\models
 */
class Orders extends ActiveRecord
{
    const MODE = [
        1 => 'Auto',
        2 => 'Manual',
    ];

    const STATUS = [
        1 => 'Pending',
        2 => 'In progress',
        3 => 'Completed',
        4 => 'Canceled',
        5 => 'Error'];

    /**
     * @return ActiveQuery
     */
    public function getServices(): ActiveQuery
    {
        return $this->hasOne(Services::className(), ['id' => 'service_id']);
    }

    /**
     * @return array
     * @throws \yii\db\Exception
     */
    public function getTotalCount(): array
    {
        return $this::find()
            ->select(['count(*) as serviceCount'])
            ->joinWith('services')
            ->createCommand()
            ->queryAll();
    }

    /**
     * @return array
     * @throws \yii\db\Exception
     */
    public function getUniqueServiceCountList(): array
    {
        return $this::find()
            ->select(['services.id','services.name','COUNT(*) AS cnt'])
            ->joinWith('services')
            ->groupBy(['services.id','services.name'])
            ->orderBy('cnt', 'DESC')
            ->createCommand()
            ->queryAll();
    }

}
