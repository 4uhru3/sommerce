<?php

namespace app\modules\orderList\controllers;

use app\modules\orderList\models\SearchOrders;
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
        $searchModel = new SearchOrders();
        $params = Yii::$app->request->get();
        $dataProvider = $searchModel->search($params);
        $params = $searchModel->getParams();

        return $this->render('index', [
                'dataProvider' => $dataProvider,
                'params' => $params,
            ]
        );
    }

    /**
     * @return \yii\console\Response|\yii\web\Response
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\RangeNotSatisfiableHttpException
     */
    public function actionDownload()
    {
        $searchModel = new SearchOrders();
        $params = yii::$app->request->get();
        $csv = $searchModel->createCSV($params);

        return Yii::$app->response->sendContentAsFile($csv, 'export.csv', [
            'mimeType' => 'application/csv',
            'inline'   => false
        ]);
    }
}