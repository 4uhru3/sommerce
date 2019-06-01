<?php
/**
 * @var $params app\modules\orderList\controllers\DefaultController
*/

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use \app\modules\orderList\models\OrdersSearch;

?>
<body>
<div class="form-inline">
<?php
    $form = ActiveForm::begin([
        'action' => [
            'index',
            'status' => $params['status'],
         ],
        'method' => 'get',
    ]);
?>
    <div class="input-group">
    <?= html::input('text', 'searchValue', '', [
            'class' => 'form-control',
            'placeholder' => $params['searchValue']
        ]) ?>
        <span class="input-group-btn search-select-wrap">
            <select class="form-control search-select" name="searchColumn">
            <?php foreach (OrdersSearch::getSearchColumn() as $value => $name): ?>
                <?php ($value == $params['searchColumn']) ? $selected = 'selected' : $selected = null; ?>
                <option <?= $selected ?> value=<?= $value ?>><?= $name ?></option>
            <?php endforeach; ?>
            </select>
            <?= html::submitButton('<span class="glyphicon glyphicon-search"  aria-hidden="true"></span>', [
                        'type' => 'submit',
                        'class' => 'btn btn-default'
                    ]) ?>
        </span>
    </div>
<?php ActiveForm::end() ?>
</div>
</body>
