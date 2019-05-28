<?php


namespace app\modules\orderList\services;


use app\modules\orderList\models\Orders;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;

class OrderTableView
{

    public function viewTable(ActiveDataProvider $dataProvider) : void 
    {
    $orderModel = $dataProvider->getModels();
    $uniqueServices = (new ServiceCounter)->countUniqueService($orderModel);

    foreach ($orderModel as $model)
        {
            echo '<tr>';
            echo('<td>' . $model->id . '</td>');
            echo('<td>' . $model->user . '</td>');
            echo('<td>' . Html::a($model->link, $model->link) . '</td>');
            echo('<td>' . $model->quantity . '</td>');
            echo('<td><span class="label-id">' . $uniqueServices[$model->services->id] .
                '</span> ' . $model->services->name . '</td>');
            echo('<td>' . Yii::t('app', (new Orders)::STATUS[$model->status]) . '</td>');
            echo('<td>' . Yii::t('app', (new Orders)::MODE[$model->mode]) . '</td>');
            echo('<td><span class="nowrap">' . Yii::$app->formatter->asDate($model->created_at) .
                '</span><span class="nowrap">' . Yii::$app->formatter->asTime($model->created_at) .
                '</span></td>');
            echo '</tr>';
        }
    }
}