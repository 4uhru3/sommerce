<?php


namespace app\modules\orderList\models;

use yii\data\ActiveDataProvider;

class OrdersSearch
{
    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function searchOrders(array $params): ActiveDataProvider
    {
        $query = Orders::find();

            $query->andFilterWhere([$params['searchColumn'] => $params['searchValue']]);
            $query->andFilterWhere(['service_id' => $params['serviceID']]);
            $query->andFilterWhere(['status' => $params['statusID']]);
            $query->andFilterWhere(['mode' => $params['modeID']]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 100
            ],
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC]
            ]

        ]);

        return $dataProvider;
    }
}