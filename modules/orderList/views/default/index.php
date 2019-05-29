<?php

/**
 * @var $this yii\web\View
 * @var $dataProvider yii\data\ActiveDataProvider
 */

$this->title = 'Order List "Sommerce"';

use app\modules\orderList\services\PageCounterService;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use \app\modules\orderList\services\ServiceCounter;
use app\modules\orderList\models\Orders;

?>

<div class="container-fluid">
    <ul class="nav nav-tabs p-b">
        <?= $this->render('_navigation', ['params' => $params]) ?>
        <li class="pull-right custom-search">
            <?= $this->render('_search', ['params' => $params]) ?>
        </li>
    </ul>

    <!--  Ссылка на экспорт в CSV  -->
    <ul class="p-b nav">
        <li class="pull-right custom-search">
            <?= Html::a(
                Yii::t('app', 'Export'),
                    Url::to([
                        'download',
                        'mode' => $params['mode'],
                        'service_id' => $params['service_id'],
                        'status' => $params['status'],
                        'searchColumn' => $params['searchColumn'],
                        'searchValue' => $params['searchValue']
                    ])
                )?>
        </li>
    </ul>
    <!--  Начало таблицы  -->
    <table class="table order-table">
        <thead>
        <tr>
            <th><?= Yii::t('app', 'ID') ?></th>
            <th><?= Yii::t('app', 'User') ?></th>
            <th><?= Yii::t('app', 'Link') ?></th>
            <th><?= Yii::t('app', 'Quantity') ?></th>
            <th class="dropdown-th">
                <div class="dropdown">
                    <button class="btn btn-th btn-default dropdown-toggle" type="button" id="dropdownMenu1"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <?= Yii::t('app', 'Service') ?>
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                        <li class="active">
                            <?= Html::a('All ' . ServiceCounter::countTotalServices(),
                                Url::to([
                                    'index',
                                    'service_id' => null,
                                    'mode' => $params['mode'],
                                    'status' => $params['status']
                                    ])
                            )?>
                        </li>
                        <?php
                        foreach (Orders::getUniqueServiceCountList() as $service)
                        {
                            echo Html::tag('li',
                                    Html::a('<span class="label-id">' . $service['cnt'] . '</span>' .  Yii::t('app', $service['name']),
                                            Url::to([
                                                'index',
                                                'service_id' => $service['id'],
                                                'mode' => $params['mode'],
                                                'status' => $params['status']
                                            ])
                                    )
                            );
                        }
                        ?>
                    </ul>
                </div>
            </th>
            <th><?= Yii::t('app', 'Status') ?></th>
            <th class="dropdown-th">
                <div class="dropdown">
                    <button class="btn btn-th btn-default dropdown-toggle" type="button" id="dropdownMenu1"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <?= Yii::t('app', 'Mode') ?>
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                        <?php
                        foreach (Orders::getModeLabel() as $key => $value) {
                            echo '<li>';
                            echo Html::a(Orders::getModeName($key),
                                 Url::to([
                                    'index',
                                    'mode' => $key,
                                    'service_id' => $params['service_id'],
                                    'status' => $params['status']
                                ]));
                            echo '</li>';
                        }
                        ?>
                    </ul>
                </div>
            </th>
            <th>
                <?= Yii::t('app', 'Created') ?>
            </th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($dataProvider->getModels() as $model)
        {
            echo '<tr>';
            echo('<td>' . $model->id . '</td>');
            echo('<td>' . $model->user . '</td>');
            echo('<td>' . Html::a($model->link, $model->link) . '</td>');
            echo('<td>' . $model->quantity . '</td>');
            echo('<td><span class="label-id">' . implode('', Orders::getServiceCount($model->services->id)) .
                '</span> ' . $model->services->name . '</td>');
            echo('<td>' . Orders::getStatusName($model->status) . '</td>');
            echo('<td>' . Orders::getModeName($model->mode) . '</td>');
            echo('<td><span class="nowrap">' . Orders::getDate($model->created_at) .
                '</span><span class="nowrap">' . Orders::getTime($model->created_at) .
                '</span></td>');
            echo '</tr>';
        }
        ?>
        </tbody>
    </table>
    <!--  Окончание таблицы  -->
    <ul class="nav nav-tabs p-b">
        <li>
            <?= LinkPager::widget(['pagination' => $dataProvider->pagination]) ?>
        </li>
        <li class="pull-right">
            <!-- Выводим количество записей-->
            <?=(new PageCounterService())->createCounter($dataProvider);?>
        </li>
    </ul>
</div>
