<?php

namespace app\modules\orderList\models;

use yii\data\ActiveDataProvider;
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
     * @return array
     */
    public function rules()
    {
        return [
            ['service_id','integer'],
            ['status','integer', 'message' => 'Not Integer'],
            ['mode','integer'],
            ['id','integer'],
            ['link', 'filter', 'filter' => 'trim'],
            ['user','filter', 'filter' => 'trim']
        ];
    }

    /**
     * @param $params
     * @return array
     */
    public function validateParams(array $params) : array
    {
        $params['status'] = isset($params['status']) ? $params['status'] : null;
        $params['service_id'] = isset($params['service_id']) ? $params['service_id'] : null;
        $params['mode'] = isset($params['mode']) ? $params['mode'] : null;
        $params['searchColumn'] = isset($params['searchColumn']) ? $params['searchColumn'] : $params['searchColumn'] = 'id';
        $params['searchValue'] = isset($params['searchValue']) ? $params['searchValue'] : null;

        return $params;
    }

    /**
     * @param $params
     * @return ActiveDataProvider
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = Orders::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 100
            ],
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC]
            ]
        ]);

            if (!($this->load($params, '') && $this->validate())) {

                return $dataProvider;
            }

        $query->andFilterWhere([$params['searchColumn'] => $params['searchValue']]);
        $query->andFilterWhere(['service_id' => $this->service_id]);
        $query->andFilterWhere(['status' => $this->status]);
        $query->andFilterWhere(['mode' => $this->mode]);

        return $dataProvider;
    }

    /**
     * @param ActiveDataProvider $dataProvider
     * @return string
     */
    public function exportCSV(ActiveDataProvider $dataProvider): string
    {
        $dataProvider->setPagination(false);
        $model = $dataProvider->getModels();
        $data = "ID;User;Link;Quantity;Service;Status;Mode;Created \r\n";
        foreach ($model as $value) {
            $data .= $value->id .
                ';' . $value->user .
                ';' . $value->link .
                ';' . $value->quantity .
                ';' . $value->services->name .
                ';' . (new Orders)::STATUS[$value->status] .
                ';' . (new Orders)::MODE[$value->mode] .
                ';' . date('Y-m-d H:i:s', $value->created_at).
                "\r\n";
        }
        header('Content-type: text/csv');
        header('Content-Disposition: attachment; filename="export_' . date('d.m.Y') . '.csv"');
        return $data;
    }
}