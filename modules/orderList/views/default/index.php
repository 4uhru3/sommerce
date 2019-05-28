<?php

/* @var $this yii\web\View */

$this->title = 'Order List "Sommerce"';

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>

<div class="container-fluid">
    <!--  Частичное представление нав-панели  -->
    <?= $this->render('_navigation', ['status' => $status, 'statusID' => $statusID]) ?>
    <!--  Ссылка на экспорт в CSV  -->
    <ul class="p-b nav">
        <li class="pull-right custom-search">
            <?= Html::a(
                    Yii::t('app', 'Export'),
                    Url::to([
                        'index',
                        'export' => true,
                        'modeID' => $modeID,
                        'serviceID' => $serviceID,
                        'statusID' => $statusID,
                        'searchColumn' => Yii::$app->request->get('searchColumn'),
                        'searchValue' => Yii::$app->request->get('searchValue')
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
                            <?= Html::a('All ' . $serviceTotal,
                                    Url::to([
                                        'index',
                                        'serviceID' => null,
                                        'modeID' => $modeID,
                                        'statusID' => $statusID])
                            )?>
                        </li>
                        <?php
                        foreach ($serviceCount as $service)
                        {
                            echo Html::tag('li',
                                    Html::a('<span class="label-id">' . $service['cnt'] . '</span>' .  Yii::t('app', $service['name']),
                                            Url::to(['index',
                                                'serviceID' => $service['id'],
                                                'modeID' => $modeID,
                                                'statusID' => $statusID])
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
                                    'serviceID' => $serviceID,
                                    'statusID' => $statusID
                                ]
                            ) ?>">
                                <?= Yii::t('app', 'All') ?>
                            </a>
                        </li>
                        <?php
                        foreach ($mode as $key => $value) {
                            echo '<li>';
                            echo Html::a(Yii::t('app', $value),
                                Url::to([
                                    'index',
                                    'modeID' => $key,
                                    'serviceID' => $serviceID,
                                    'statusID' => $statusID
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
        foreach ($orderModel as $model) {
            echo '<tr>';
            echo('<td>' . $model->id . '</td>');
            echo('<td>' . $model->user . '</td>');
            echo('<td>' . Html::a($model->link, $model->link) . '</td>');
            echo('<td>' . $model->quantity . '</td>');
            echo('<td><span class="label-id">' . $uniqueServices[$model->services->id] .
                '</span> ' . $model->services->name . '</td>');
            echo('<td>' . Yii::t('app',$status[$model->status]) . '</td>');
            echo('<td>' . Yii::t('app', $mode[$model->mode]) . '</td>');
            echo('<td><span class="nowrap">' . Yii::$app->formatter->asDate($model->created_at) .
                '</span><span class="nowrap">' . Yii::$app->formatter->asTime($model->created_at) .
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
            <?=$pageCounter?>
        </li>
    </ul>
</div>
