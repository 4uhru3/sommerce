<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
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
     * @param array $params
     * @return ActiveDataProvider
     */
    public function getDataProvider(array $params): ActiveDataProvider
    {
        $modeID = isset($params['modeID']) ? $params['modeID'] : null;
        $serviceID = isset($params['serviceID']) ? $params['serviceID'] : null;
        $statusID = isset($params['statusID']) ? $params['statusID'] : null;
        $searchColumn = isset($params['searchColumn']) ? $params['searchColumn'] : null;
        $searchValue = isset($params['searchValue']) ? $params['searchValue'] : null;

        $query = Orders::find();

        if ($searchColumn & $searchValue) {
            $query->andFilterWhere([$searchColumn => $searchValue]);
        }

        if ($serviceID) {
            $query->andFilterWhere(['service_id' => $serviceID]);
        }

        if ($statusID) {
            $query->andFilterWhere(['status_id' => $statusID]);
        }

        if ($modeID) {
            $query->andFilterWhere(['mode_id' => $modeID]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;
    }
}
