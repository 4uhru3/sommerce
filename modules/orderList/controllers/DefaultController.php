<?php

namespace app\modules\orderList\controllers;

use app\modules\orderList\models\OrdersExport;
use app\modules\orderList\models\OrdersSearch;
use yii\web\Controller;
use Yii;

class DefaultController extends Controller
{
    /**
     * @return string
     * @throws \yii\db\Exception
     */
    public function actionIndex()
    {
        $params = Yii::$app->request->get();

        if(isset($params['lang']))
        {
            Yii::$app->language = $params['lang'];
        }
        $params['statusID'] = isset($params['statusID']) ? $params['statusID'] : null;
        $params['serviceID'] = isset($params['serviceID']) ? $params['serviceID'] : null;
        $params['modeID'] = isset($params['modeID']) ? $params['modeID'] : null;
        $params['searchColumn'] = isset($params['searchColumn']) ? $params['searchColumn'] : null;
        $params['searchValue'] = isset($params['searchValue']) ? $params['searchValue'] : null;

        $dataProvider = (new OrdersSearch)->searchOrders($params);

        isset($params['export']) ? (new OrdersExport)->exportCSV($dataProvider) : false;

        return $this->render('index', [
                'dataProvider' => $dataProvider,
                'params' => $params,
            ]
        );
    }
}