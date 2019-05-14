<?php

namespace app\controllers;

use app\services\PageCounterService;
use yii\web\Controller;
use app\models\Orders;
use Yii;

/**
 * Class SiteController
 * @package app\controllers
 */
class SiteController extends Controller
{
    /**
     * @return string
     * @throws \yii\db\Exception
     */
    public function actionIndex()
    {
        //Устанавливаем язык приложения и пишем его в сессию
        $session = Yii::$app->session;
        $session->open();
        $lang = Yii::$app->request->get('lang');
        if(isset($lang)){
            $session->set('lang',$lang);
        }
        Yii::$app->language = $session->get('lang');

        $orders = new Orders();

        //Передаем значения фильтров, и получаем отфильтрованый провайдер по 100 записей на страницу.
        $dataProvider = $orders->getDataProvider(Yii::$app->request->get());
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
        $status = $orders->getStatusTable();
        $mode = $orders->getModeTable();

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