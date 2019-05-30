<?php

namespace app\modules\orderList\models;

use yii\db\ActiveRecord;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use Yii;

/**
 * Class Orders
 * @package app\models
 */
class Orders extends ActiveRecord
{
    const MODE_ALL = null;
    const MODE_AUTO = 1;
    const MODE_MANUAL = 2;

    const STATUS_ALL_ORDERS = null;
    const STATUS_PENDING = 1;
    const STATUS_IN_PROGRESS = 2;
    const STATUS_COMPLETED = 3;
    const STATUS_CANCELED = 4;
    const STATUS_ERROR = 5;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'orders';
    }

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
        return self::find()
            ->select(['services.id','services.name','COUNT(*) AS cnt'])
            ->joinWith('services')
            ->groupBy(['services.id','services.name'])
            ->orderBy('cnt', 'DESC')
            ->createCommand()
            ->queryAll();
    }

    /**
     * @param $id
     * @return array
     * @throws \yii\db\Exception
     */
    public function getServiceCount($id): array
    {
        return self::find()
            ->select(['COUNT(*) AS cnt'])
            ->where(['services.id' => $id])
            ->joinWith('services')
            ->groupBy(['services.id'])
            ->createCommand()
            ->queryOne();
    }

    /**
     * @return array
     */
    public function getModeLabel(): array
    {
        return [
            self::MODE_ALL => 'All',
            self::MODE_AUTO => 'Auto',
            self::MODE_MANUAL => 'Manual',
        ];
    }

    /**
     * @param $id
     * @return string
     */
    public function getModeName($id): string
    {
        return Yii::t('app', ArrayHelper::getValue(self::getModeLabel(), $id));
    }

    /**
     * @return array
     */
    public function getStatusLabel(): array
    {
        return [
            self::STATUS_ALL_ORDERS => 'All orders',
            self::STATUS_PENDING => 'Pending',
            self::STATUS_IN_PROGRESS => 'In progress',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_CANCELED => 'Canceled',
            self::STATUS_ERROR => 'Error'
        ];
    }

    /**
     * @param $id
     * @return string
     */
    public function getStatusName($id): string
    {
        return Yii::t('app', ArrayHelper::getValue(self::getStatusLabel(), $id));
    }

    /**
     * @param $date
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function getDate($date): string
    {
       return Yii::$app->formatter->asDate($date);
    }

    /**
     * @param $date
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function getTime($date): string
    {
        return Yii::$app->formatter->asTime($date);
    }
}
