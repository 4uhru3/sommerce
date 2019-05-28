<?php

namespace app\modules\orderList\controllers;

use app\modules\orderList\models\Orders;
use yii\web\Controller;
use Yii;

/**
 * Class DefaultController
 * @package app\modules\orderList\controllers
 */
class DefaultController extends Controller
{
    /**
     * @return string
     */
    public function actionIndex()
    {

        $params = (new Orders)->validateParams(Yii::$app->request->get());

        $dataProvider = (new Orders)->search($params);

        isset($params['export']) ? (new Orders)->exportCSV($params) : false;

        $ordersModel = $dataProvider->getModels();

        return $this->render('index', [
                'dataProvider' => $dataProvider,
                'params' => $params,
                'ordersModel' => $ordersModel,
            ]
        );
    }
}