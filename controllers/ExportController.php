<?php

namespace app\controllers;

use yii\web\Controller;
use app\models\Orders;
use Yii;

/**
 * Class ExportController
 * @package app\controllers
 */
class ExportController extends Controller
{
    /**
     * Экспорт заказов в формате csv
     */
    public function actionExport()
    {
        $searchModel = new Orders();

        $dataProvider = $searchModel->search(Yii::$app->request->get());
        $dataProvider->setPagination([
            'pageSize' => $dataProvider->query->count(),
        ]);
        $model = $dataProvider->getModels();

        $data = "ID;User;Link;Quantity;Service;Status;Mode;Created \r\n";
        foreach ($model as $value) {
            $data .= $value->id .
                ';' . $value->user .
                ';' . $value->link .
                ';' . $value->quantity .
                ';' . $value->services->name .
                ';' . $value->status->name .
                ';' . $value->mode->name .
                ';' . date('Y-m-d H:i:s', $value->created_at).
                "\r\n";
        }
        header('Content-type: text/csv');
        header('Content-Disposition: attachment; filename="export_' . date('d.m.Y') . '.csv"');

        echo $data;
        die;
    }
}
