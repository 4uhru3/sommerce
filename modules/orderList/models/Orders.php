<?php

namespace app\modules\orderList\models;

use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
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

    /**
     * @param $id
     * @return array
     * @throws \yii\db\Exception
     */
    public function getServiceCount($id): array
    {
        return $this::find()
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
    public function rules()
    {
        return [
            ['service_id','integer'],
            ['status','integer', 'message' => 'Not Integer'],
            ['mode','integer'],
            ['id','integer'],
            ['link', 'filter', 'filter' => 'trim', 'skipOnArray' => true],
            ['user','filter', 'filter' => 'trim', 'skipOnArray' => true]
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
     * @param $params
     * @return string
     */
    public function exportCSV(array $params): string
    {
        $dataProvider = $this->search($params);
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

        echo $data;
        die;
    }
}
