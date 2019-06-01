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
     * @return string
     * @throws \yii\db\Exception
     */
    public function getTotalCount(): string
    {
        return self::find()->count();
    }

    /**
     * @return array
     * @throws \yii\db\Exception
     */
    public function getServiceList(): array
    {
        return self::find()
            ->select(['services.id','services.name','COUNT(*) AS cnt'])
            ->joinWith('services')
            ->groupBy(['services.id','services.name'])
            ->orderBy(['cnt' => SORT_DESC])
            ->createCommand()
            ->queryAll();
    }

    /**
     * @return array
     * @throws \yii\db\Exception
     */
    public function servicesFilter(): array
    {
        $all = [[
            'id' => null,
            'name' => 'All',
            'cnt' => self::getTotalCount(),
            'is_active' => true
        ]];

        $services = [];
        foreach ($this->getServiceList() as $serviceList) {
            $services[] = [
                'id' => $serviceList['id'],
                'name' => $serviceList['name'],
                'cnt' => $serviceList['cnt'],
                'is_active' => false
            ];
        }

        return array_merge($all, $services);
    }

    /**
     * @return array
     */
    public function getModeLabel(): array
    {
        return [
            self::MODE_ALL => Yii::t('app','All'),
            self::MODE_AUTO => Yii::t('app','Auto'),
            self::MODE_MANUAL => Yii::t('app','Manual'),
        ];
    }

    /**
     * @return string
     */
    public function getModeName(): string
    {
        return Yii::t('app', ArrayHelper::getValue(self::getModeLabel(),$this->mode));
    }

    /**
     * @return array
     */
    public static function getStatusLabel(): array
    {
        return [
            self::STATUS_ALL_ORDERS => Yii::t('app','All orders'),
            self::STATUS_PENDING => Yii::t('app','Pending'),
            self::STATUS_IN_PROGRESS => Yii::t('app','In progress'),
            self::STATUS_COMPLETED => Yii::t('app','Completed'),
            self::STATUS_CANCELED => Yii::t('app','Canceled'),
            self::STATUS_ERROR => Yii::t('app','Error')
        ];
    }

    /**
     * @return string
     */
    public function getStatusName(): string
    {
        return Yii::t('app', ArrayHelper::getValue($this->statusLabel, $this->status));
    }

    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function getDate(): string
    {
       return Yii::$app->formatter->asDate($this->created_at);
    }

    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function getTime(): string
    {
        return Yii::$app->formatter->asTime($this->created_at);
    }
}
