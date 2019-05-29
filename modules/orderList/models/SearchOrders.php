<?php

namespace app\modules\orderList\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Class SearchOrders
 * @package app\modules\orderList\models
 */
class SearchOrders extends Orders
{
    public $searchColumn;
    public $searchValue;

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
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['service_id'],'integer'],
            [['service_id'],'default'],
            [['status'],'integer'],
            [['status'],'default'],
            [['mode'],'integer'],
            [['mode'],'default'],
            [['id'],'integer'],
            [['id'],'filter','filter' => 'trim'],
            [['link'], 'filter', 'filter' => 'trim' ],
            [['user'],'filter', 'filter' => 'trim'],
            [['searchColumn'], 'default'],
            [['searchValue'], 'default']
        ];
    }

    /**
     * @param $params
     * @return array
     */
    public function getParams(): array
    {
        $params = $this->attributes;
        $params['searchColumn'] = $this->searchColumn;
        $params['searchValue'] = $this->searchValue;

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
                'pageSize' => Orders::PAGE_SIZE
            ],
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC]
            ]
        ]);

        if (!($this->load($params, '') && $this->validate())) {

            return $dataProvider;
        }

        $query->andFilterWhere([$this->searchColumn => $this->searchValue]);
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