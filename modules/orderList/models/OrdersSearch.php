<?php

namespace app\modules\orderList\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * OrdersSearch represents the model behind the search form of `app\modules\orderList\models\Orders`.
 */
class OrdersSearch extends Orders
{
    const PAGE_SIZE = 100;

    const SEARCH_COLUMN_VALUE_ID = 'id';
    const SEARCH_COLUMN_VALUE_LINK = 'link';
    const SEARCH_COLUMN_VALUE_USER = 'user';

    public $searchColumn;
    public $searchValue;

    /**
     * {@inheritdoc}
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
            [['link'], 'url'],
            [['user'],'filter', 'filter' => 'trim'],
            [['searchColumn'], 'default'],
            [['searchValue'], 'default'],
            [['searchValue'], 'filter', 'filter' => 'trim']
        ];
    }

    /**
     * @return array
     */
    public static function getSearchColumn(): array
    {
        return [
            self::SEARCH_COLUMN_VALUE_ID => Yii::t('app', 'Order ID'),
            self::SEARCH_COLUMN_VALUE_LINK => Yii::t('app', 'Link'),
            self::SEARCH_COLUMN_VALUE_USER => Yii::t('app', 'User')
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        return Model::scenarios();
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
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = Orders::find()->with('services');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => self::PAGE_SIZE
            ],
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC]
            ]
        ]);

        if (!($this->load($params, '') && $this->validate())) {

            return $dataProvider;
        }

        $query->andFilterWhere([$this->searchColumn => $this->searchValue])
            ->andFilterWhere(['service_id' => $this->service_id])
            ->andFilterWhere(['status' => $this->status])
            ->andFilterWhere(['mode' => $this->mode]);

        return $dataProvider;
    }

    /**
     * @param array $params
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function createCSV($params): string
    {
        $dataProvider = (new OrdersSearch)->search($params);
        $dataProvider->setPagination(false);
        $dataProvider->setSort([
            'defaultOrder' => ['id' => SORT_DESC]
        ]);
        $model = $dataProvider->getModels();

        $data = "ID;User;Link;Quantity;Service;Status;Mode;Created \r\n";
        foreach ($model as $value) {
            $data .= $value->id .
                ';' . $value->user .
                ';' . $value->link .
                ';' . $value->quantity .
                ';' . $value->services->name .
                ';' . $value->statusName .
                ';' . $value->modeName .
                ';' . $value->date . "/" . $value->time .
                "\r\n";
        }

        return $data;
    }
}
