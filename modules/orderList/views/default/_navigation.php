<?php
/**
 * @var $orders app\modules\orderList\models\Orders
 * @var $params
 */

use yii\helpers\Html;

?>
<?php foreach ($orders->getStatusLabel() as $status => $value): ?>
    <?php ($params['status'] == $status) ? $cssClass = 'active' : $cssClass = null; ?>
<li class =<?= $cssClass ?>>
    <?= Html::a($value, ['index', 'status' => $status])?>
</li>
<?php endforeach; ?>