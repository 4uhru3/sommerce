<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
 <ul class="nav nav-tabs p-b">
        <li class="<?php if ($statusID == null) {echo('active');}; ?>">
            <a href="<?= URL::to(['index', 'statusID' => null]) ?>">
                <?= Yii::t('app', 'All orders') ?>
            </a>
        </li>
        <?php
        foreach ($status as $key => $value)
        {
            if ($statusID == $value['id'])
            {
                $cssClass = 'active';
            }
            else $cssClass = null;
            echo Html::tag('li',
                    Html::a(
                        Yii::t('app', $value['name']),
                        url::to(['index', 'statusID' => $value['id']])),
                ['class' => $cssClass]
            );
        } ?>
    <!-- Частичное представление для формы поиска       -->
        <li class="pull-right custom-search">
            <?= $this->render('_search', ['statusID' => $statusID,]) ?>
        </li>
 </ul>