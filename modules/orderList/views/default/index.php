<?php

/* @var $this yii\web\View */

$this->title = 'Order List "Sommerce"';

use app\modules\orderList\services\PageCounterService;
use app\modules\orderList\models\Orders;
use \app\modules\orderList\services\OrderTableView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use \app\modules\orderList\services\ServiceCounter;
?>

<div class="container-fluid">
    <!--  Частичное представление нав-панели  -->
    <?= $this->render('_navigation', ['status' => (new Orders)::STATUS, 'statusID' => $params['statusID']]) ?>
    <!--  Ссылка на экспорт в CSV  -->
    <ul class="p-b nav">
        <li class="pull-right custom-search">
            <?= Html::a(
                    Yii::t('app', 'Export'),
                    Url::to([
                        'index',
                        'export' => true,
//                        'modeID' => $params['modeID'],
//                        'serviceID' => $params['serviceID'],
//                        'statusID' => $params['statusID'],
//                        'searchColumn' => $params['searchColumn'],
//                        'searchValue' => $params['searchValue']
                    $params
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
                            <?= Html::a('All ' . (new ServiceCounter)->countTotalServices(),
                                    Url::to([
                                        'index',
                                        'serviceID' => null,
                                        'modeID' => $params['modeID'],
                                        'statusID' => $params['statusID']])
                            )?>
                        </li>
                        <?php
                        foreach ((new Orders)->getUniqueServiceCountList() as $service)
                        {
                            echo Html::tag('li',
                                    Html::a('<span class="label-id">' . $service['cnt'] . '</span>' .  Yii::t('app', $service['name']),
                                            Url::to(['index',
                                                'serviceID' => $service['id'],
                                                'modeID' => $params['modeID'],
                                                'statusID' => $params['statusID']])
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
                        <li class="active">
                            <a href="<?= Url::to(
                                [
                                    'index',
                                    'modeID' => null,
                                    'serviceID' => $params['serviceID'],
                                    'statusID' => $params['statusID']
                                ]
                            ) ?>">
                                <?= Yii::t('app', 'All') ?>
                            </a>
                        </li>
                        <?php
                        foreach ((new Orders)::MODE as $key => $value) {
                            echo '<li>';
                            echo Html::a(Yii::t('app', $value),
                                Url::to([
                                    'index',
                                    'modeID' => $key,
                                    'serviceID' => $params['serviceID'],
                                    'statusID' => $params['statusID']
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
        <?php (new OrderTableView)->viewTable($dataProvider)?>
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
