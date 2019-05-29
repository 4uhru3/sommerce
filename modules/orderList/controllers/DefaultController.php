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
        $orders = new Orders;

        $params = $orders->validateParams(Yii::$app->request->get());

        $dataProvider = $orders->search($params);

        isset($params['export']) ? $orders->exportCSV($dataProvider) : false;

        return $this->render('index', [
                'dataProvider' => $dataProvider,
                'params' => $params,
            ]
        );
    }
}