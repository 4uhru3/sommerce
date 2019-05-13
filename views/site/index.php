<?php

/* @var $this yii\web\View */

$this->title = 'Order List "Sommers"';

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

$sum_page = $dataProvider->pagination->page;
$sum_limit = $dataProvider->pagination->limit;
$sum_offset = $dataProvider->pagination->offset;
$sum_total = $dataProvider->totalCount;
$sum_count = $dataProvider->count;
?>

<div class="container-fluid">
    <ul class="nav nav-tabs p-b">
        <li class="<?php if ($statusID == null) {
            echo('active');
        }; ?>">
            <a href="<?= URL::to(['index', 'statusID' => null]) ?>">
                <?= Yii::t('app', 'All orders') ?>
            </a>
        </li>
        <?php
        foreach ($status as $key => $value) {
            if ($statusID == $value['id']) {
                $cssClass = 'active';
            } else $cssClass = null;
            echo Html::beginTag('li', ['class' => $cssClass]);
            echo(html::a(Yii::t('app', $value['name']),
                url::to(['index', 'statusID' => $value['id']])));
            echo "</li>";
        } ?>
        <li class="pull-right custom-search">
            <?= $this->render(
                '_search',
                [
                    'model' => $searchModel,
                    'statusID' => $statusID,

                ]) ?>
        </li>
    </ul>
    <!-- Вывод количества записей-->

    <span><?= $summary = $sum_page * $sum_limit + 1; ?>
    </span><?= Yii::t('app', 'to') ?>
    <span>
    <?php if (($sum_offset + $sum_limit) < $sum_total) {
        echo $summary = ($sum_offset + $sum_limit);
    } else {
        echo $sum_total;
    }
    ?>
    </span>
    <?= Yii::t('app', 'of') ?>
    <span>
      <?= $sum_total ?>
    </span>

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
                        <li class="active"><?php echo(html::a(
                                'All ' . $serviceTotal, url::to([
                                'index', 'serviceID' => null, 'modeID' => $modeID, 'statusID' => $statusID]))); ?>
                        </li>
                        <?php
                        foreach ($serviceCount as $service) {
                            echo '<li>';
                            echo html::a(
                                '<span class="label-id">' . $service['cnt'] . '</span>' . Yii::t('app', $service['name']),
                                url::to(['index', 'serviceID' => $service['id'],
                                    'modeID' => $modeID, 'statusID' => $statusID]));
                            echo '</li>';
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
                            <a href="<?= URL::to(
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
                        foreach ($mode as $value) {
                            echo '<li>';
                            echo HTML::a(Yii::t('app', $value['name']),
                                URL::to([
                                    'index',
                                    'modeID' => $value['id'],
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
            echo('<td>' . html::a($model->link, $model->link) . '</td>');
            echo('<td>' . $model->quantity . '</td>');
            echo('<td>
                    <span class="label-id">' . $uniqueServices[$model->services->id] . '</span> ' . $model->services->name . '
                  </td>');
            echo('<td>' . $model->status->name . '</td>');
            echo('<td>' . $model->mode->name . '</td>');
            echo('<td><span class="nowrap">' .
                    Yii::$app->formatter->asDate($model->created_at) .
                '</span><span class="nowrap">' . Yii::$app->formatter->asTime($model->created_at) .
                '</span></td>');
            echo '</tr>';
        }
        ?>
        </tbody>
    </table>
    <ul>
        <li class="pull-right custom-search">
            <?= LinkPager::widget(['pagination' => $dataProvider->pagination]) ?>
        </li>
        <li class="pull-right custom-search">
            <?=
            HTML::a(
                Yii::t('app', 'Export'),
                url::to([
                    'export/export',
                    'modeID' => $modeID,
                    'serviceID' => $serviceID,
                    'statusID' => $statusID,
                    'Orders' => Yii::$app->request->get('Orders'),
                    'searchType' => Yii::$app->request->get('searchType'),
                ])
            ) ?>
        </li>
    </ul>
</div>

