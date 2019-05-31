<?php

/**
 * @var $this yii\web\View
 * @var $serviceCount
 * @var $orders app\modules\orderList\models\Orders
 * @var $dataProvider yii\data\ActiveDataProvider
 * @var $params
 */

$this->title = 'Order List "Sommerce"';

use app\modules\orderList\services\PageCounterService;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use \app\modules\orderList\services\ServiceCounter;

?>
<div class="container-fluid">
    <ul class="nav nav-tabs p-b">
        <?= $this->render('_navigation', ['params' => $params, 'orders' => $orders]) ?>
        <li class="pull-right custom-search">
            <?= $this->render('_search', ['params' => $params]) ?>
        </li>
    </ul>
    <ul class="p-b nav">
        <li class="pull-right custom-search">
            <?= Html::a(Yii::t('app', 'Export'), array_merge(['download'], $params)) ?>
        </li>
    </ul>
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
                        <?php foreach ($orders->servicesFilter() as $service): ?>
                            <?php ($service['is_active'] == true) ? $cssClass = 'active' : $cssClass = null; ?>
                        <li class=<?= $cssClass ?>>
                            <a href=<?= Url::to(['index',
                                                'service_id' => $service['id'],
                                                'mode' => $params['mode'],
                                                'status' => $params['status']
                                            ]) ?>>
                                <span class="label-id"><?= $service['cnt'] ?></span>
                                <?= Yii::t('app', $service['name']) ?>
                            </a>
                        </li>
                       <?php endforeach; ?>
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
                        <?php foreach ($orders->getModeLabel() as $mode => $value): ?>
                            <li><?= Html::a($orders->getModeName($mode), [
                                    'index',
                                    'mode' => $mode,
                                    'service_id' => $params['service_id'],
                                    'status' => $params['status']
                                ]) ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </th>
            <th>
                <?= Yii::t('app', 'Created') ?>
            </th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($dataProvider->getModels() as $order): ?>
            <tr>
            <td><?= $order->id ?></td>
            <td><?= $order->user ?></td>
            <td><?=Html::a($order->link, $order->link) ?></td>
            <td><?= $order->quantity ?></td>
            <td><span class="label-id"><?= $serviceCount[$order->services->id] ?></span>
                <?= $order->services->name ?></td>
            <td><?= $orders->getStatusName($order->status) ?></td>
            <td><?= $orders->getModeName($order->mode) ?></td>
            <td><span class="nowrap"><?= $orders->getDate($order->created_at) ?>
                </span><span class="nowrap"><?= $orders->getTime($order->created_at) ?>
                </span></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <ul class="nav nav-tabs p-b">
        <li>
            <?= LinkPager::widget(['pagination' => $dataProvider->pagination]) ?>
        </li>
        <li class="pull-right">
            <?= (new PageCounterService())->createCounter($dataProvider); ?>
        </li>
    </ul>
</div>
