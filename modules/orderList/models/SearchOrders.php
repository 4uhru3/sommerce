<?php

namespace app\modules\orderList\models;

use yii\data\ActiveDataProvider;

class SearchOrders extends Orders
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'orders';
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
    public function validateParams(array $params): array
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
     * @param array $params
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function createCSV($params): string
    {
        $dataProvider = (new SearchOrders)->search($params);
        $dataProvider->setPagination(false);
        $dataProvider->setSort(['defaultOrder' => ['id' => SORT_DESC]]);

        $model = $dataProvider->getModels();

        $data = "ID;User;Link;Quantity;Service;Status;Mode;Created \r\n";
        foreach ($model as $value) {
            $data .= $value->id .
                ';' . $value->user .
                ';' . $value->link .
                ';' . $value->quantity .
                ';' . $value->services->name .
                ';' . self::getStatusName($value->status) .
                ';' . self::getModeName($value->mode) .
                ';' . self::getDate($value->created_at) . "/" . self::getTime($value->created_at) .
                "\r\n";
        }
        return $data;
    }
}