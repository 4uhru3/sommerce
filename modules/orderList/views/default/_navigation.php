<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\modules\orderList\models\Orders;
?>
 <ul class="nav nav-tabs p-b">
        <li class="<?php if ($params['status'] == null) {echo('active');}; ?>">
            <a href="<?= URL::to(['index', 'status' => null]) ?>">
                <?= Yii::t('app', 'All orders') ?>
            </a>
        </li>
        <?php
        foreach ((new Orders)::STATUS as $key => $value)
        {
            if ($params['status'] == $key)
            {
                //var_dump($params['Orders']['status']);die;
                $cssClass = 'active';
            }
            else $cssClass = null;
            echo Html::tag('li',
                    Html::a(
                        Yii::t('app', $value),
                        url::to(['index', 'status' => $key])),
                ['class' => $cssClass]
            );
        } ?>
    <!-- Частичное представление для формы поиска       -->
        <li class="pull-right custom-search">
            <?= $this->render('_search', ['params' => $params]) ?>
        </li>
 </ul>