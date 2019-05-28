<?php

namespace app\modules\orderList\controllers;

use app\modules\orderList\models\OrdersExport;
use app\modules\orderList\services\PageCounterService;
use app\modules\orderList\models\Orders;
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

        $orders = new Orders();

        //Передаем значения фильтров, и получаем отфильтрованый провайдер по 100 записей на страницу.
        $dataProvider = $orders->getDataProvider($params);

        isset($params['export']) ? (new OrdersExport)->exportCSV($dataProvider, $orders) : false;

        $dataProvider->setPagination(['pageSize' => 100]);
        $dataProvider->setSort(['defaultOrder' => ['id' => SORT_DESC]]);

        //Получаем модель данных с учетом пагинации.
        $orderModel = $dataProvider->getModels();

        //Получаем общее количество заказов по каждому сервису
        $serviceCount = $orders->getUniqueServiceCountList();
        $serviceTotal = (new Orders())->getTotalCount();
        foreach ($serviceTotal as $key => $value) {
            $serviceTotal = ($value['serviceCount']);
        }

        //Получаем таблицы Статус и Мод из модели
        $status = $orders::STATUS;
        $mode = $orders::MODE;

        //Количество заказов по каждому сервису для данной выборки
        $uniqueServices = $this->countUniqueService($orderModel);

        //Получаем значения фильтров
        $statusID = yii::$app->request->get('statusID');
        $serviceID = yii::$app->request->get('serviceID');
        $modeID = yii::$app->request->get('modeID');

        //Получаем PageCounter
        $pageCounter = (new PageCounterService())->createCounter($dataProvider);



        return $this->render('index', [
                'dataProvider' => $dataProvider,
                'status' => $status,
                'statusID' => $statusID,
                'serviceID' => $serviceID,
                'modeID' => $modeID,
                'orderModel' => $orderModel,
                'serviceCount' => $serviceCount,
                'serviceTotal' => $serviceTotal,
                'mode' => $mode,
                'uniqueServices' => $uniqueServices,
                'pageCounter' => $pageCounter
            ]
        );
    }

    /**
     * @param array $model
     * @return array
     */
    protected function countUniqueService(array $model): array
    {
        $uniqueServices = [];

        foreach ($model as $order) {
            if (isset($uniqueServices[$order->services->id])) {
                $uniqueServices[$order->services->id]++;
            } else {
                $uniqueServices[$order->services->id] = 1;
            }
        }
        return $uniqueServices;
    }
}