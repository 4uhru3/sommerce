<?php
/**
 * @var $orderModel app\modules\orderList\models\Orders
 * @var $params app\modules\orderList\controllers\DefaultController
 */

use yii\helpers\Html;
use app\modules\orderList\models\Orders;

?>
<?php foreach (Orders::getStatusLabel() as $status => $value): ?>
    <?php ($params['status'] == $status) ? $cssClass = 'active' : $cssClass = null; ?>
<li class =<?= $cssClass ?>>
<?= Html::a($value, ['index', 'status' => $status])?>
</li>
<?php endforeach; ?>