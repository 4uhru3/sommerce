<?php

use app\modules\orderList\models\Orders;
use yii\helpers\Html;
use yii\helpers\Url;
?>
 <ul class="nav nav-tabs p-b">
        <li class="<?php if ($params['statusID'] == null) {echo('active');}; ?>">
            <a href="<?= URL::to(['index', 'statusID' => null]) ?>">
                <?= Yii::t('app', 'All orders') ?>
            </a>
        </li>
        <?php
        foreach ((new Orders)::STATUS as $key => $value)
        {
            if ($params['statusID'] == $key)
            {
                $cssClass = 'active';
            }
            else $cssClass = null;
            echo Html::tag('li',
                    Html::a(
                        Yii::t('app', $value),
                        url::to(['index', 'statusID' => $key])),
                ['class' => $cssClass]
            );
        } ?>
    <!-- Частичное представление для формы поиска       -->
        <li class="pull-right custom-search">
            <?= $this->render('_search', ['params' => $params,]) ?>
        </li>
 </ul>