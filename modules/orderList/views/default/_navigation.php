<?php

use yii\helpers\Url;
use yii\helpers\Html;
use app\modules\orderList\models\Orders;

    foreach (Orders::getStatusLabel() as $key => $value) {
         if ($params['status'] == $key) {
             $cssClass = 'active';
         }
         else $cssClass = null;

         echo Html::tag(
             'li',
                    Html::a(
                        Orders::getStatusName($key),
                        url::to([
                            'index',
                            'status' => $key
                        ])
                    ),
             ['class' => $cssClass]);
    }