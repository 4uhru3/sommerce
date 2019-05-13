<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use yii\base\Model;
use Yii;
use yii\db\ActiveQuery;

/**
 * Class Orders
 * @package app\models
 */
class Orders extends ActiveRecord
{
    /**
     * @return ActiveQuery
     */
    public function getServices(): ActiveQuery
    {
        return $this->hasOne(Services::className(), ['id' => 'service_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getStatus(): ActiveQuery
    {
        return $this->hasOne(Status::className(), ['id' => 'status_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getMode(): ActiveQuery
    {
        return $this->hasOne(Mode::className(), ['id' => 'mode_id']);
    }

    /**
     * @return array
     * @throws \yii\db\Exception
     */
    public function getStatusTable(): array
    {
        return Yii::$app->db->createCommand('SELECT * FROM status')->queryAll();
    }

    /**
     * @return array
     * @throws \yii\db\Exception
     */
    public function getModeTable(): array
    {
        return Yii::$app->db->createCommand('SELECT * FROM mode')->queryAll();
    }

    /**
     * @return array
     * @throws \yii\db\Exception
     */
    public function getCount(): array
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
     * @return array
     */
    public function rules(): array
    {
        // только поля определенные в rules() будут доступны для поиска
        return [
              [['id'], 'integer'],
              [['user'], 'string'],
              [['link'], 'string'],
        ];
    }

    /**
     * @return array
     */
    public function scenarios(): array
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * @param $params
     * @return ActiveDataProvider
     */
    public function search($params): ActiveDataProvider
    {
        $modeID = isset($params['modeID']) ? $params['modeID'] : null;
        $serviceID = isset($params['serviceID']) ? $params['serviceID'] : null;
        $statusID = isset($params['statusID']) ? $params['statusID'] : null;

        $query = Orders::find();

        if ($serviceID) {
            $query->andWhere(['service_id' => $serviceID]);
        }

        if ($statusID) {
            $query->andWhere(['status_id' => $statusID]);
        }

        if ($modeID) {
            $query->andWhere(['mode_id' => $modeID]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        // загружаем данные формы поиска и производим валидацию
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        };

        // изменяем запрос добавляя в его фильтрацию
        $query->andFilterWhere(['id' => $this->id]);
        $query->andFilterWhere(['link' => $this->link]);
        $query->andFilterWhere(['user' => $this->user]);

        return $dataProvider;
    }
}
