<?php

namespace app\modules\orderList\controllers;

use app\modules\orderList\models\OrdersSearch;
use yii\web\Controller;
use Yii;
use yii\web\Response;

/**
 * Class DefaultController
 * @package app\modules\orderList\controllers
 */
class DefaultController extends Controller
{
    /**
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new OrdersSearch();
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
    public function actionDownload(): Response
    {
        $searchModel = new OrdersSearch();
        $params = yii::$app->request->get();
        $csv = $searchModel->createCSV($params);

        return Yii::$app->response->sendContentAsFile($csv, 'export.csv', [
            'mimeType' => 'application/csv',
            'inline'   => false
        ]);
    }
}
