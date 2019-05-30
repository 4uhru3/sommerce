<?php

namespace app\modules\orderList\models;

use yii\db\ActiveRecord;
use yii\db\ActiveQuery;
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
    public static function getUniqueServiceCountList(): array
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
    public static function getServiceCount($id): array
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
    public static function getModeLabel(): array
    {
        $mode = [
            self::MODE_ALL => 'All',
            self::MODE_AUTO => 'Auto',
            self::MODE_MANUAL => 'Manual',
        ];

        return $mode;
    }

    /**
     * @param $id
     * @return string
     */
    public static function getModeName($id): string
    {
        $result = '';
        $mode = self::getModeLabel();
        foreach ($mode as $key => $value){
            if($key == $id){
                $result = $value;
            }
        }

        return Yii::t('app', $result);
    }

    /**
     * @return array
     */
    public static function getStatusLabel(): array
    {
        $status = [
            self::STATUS_ALL_ORDERS => 'All orders',
            self::STATUS_PENDING => 'Pending',
            self::STATUS_IN_PROGRESS => 'In progress',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_CANCELED => 'Canceled',
            self::STATUS_ERROR => 'Error'
        ];

        return $status;
    }

    /**
     * @param $id
     * @return string
     */
    public static function getStatusName($id): string
    {
        $result = '';
        $status = self::getStatusLabel();
        foreach ($status as $key => $value){
            if($key == $id){
                $result = $value;
            }
        }

        return Yii::t('app', $result);
    }

    /**
     * @param $date
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public static function getDate($date): string
    {
       return Yii::$app->formatter->asDate($date);
    }

    /**
     * @param $date
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public static function getTime($date): string
    {
        return Yii::$app->formatter->asTime($date);
    }
}
