<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use \app\modules\orderList\models\OrdersSearch;

?>

<body>
<div class="form-inline">
<?php $form = ActiveForm::begin([
    'action' => ['index', 'status' => $params['status']],
    'method' => 'get',
]); ?>
    <div class="input-group">
        <?= html::input(
                'text',
                'searchValue',
                '',
                [
                'class' => 'form-control',
                'placeholder' => Yii::t('app', 'Search text')
                ])?>
        <span class="input-group-btn search-select-wrap">
                    <select class="form-control search-select" name="searchColumn">
                        <?php foreach (OrdersSearch::SEARCH_COLUMN_VALUE as $key => $value):?>
                            <option value=<?=$key?>><?= OrdersSearch::getSearchColumnOptionName($key)?></option>
                        <?php endforeach;?>
                    </select>
            <?= html::submitButton(
                    '<span class="glyphicon glyphicon-search"  aria-hidden="true"></span>', [
                        'type' => 'submit',
                        'class' => 'btn btn-default'
                    ])?>
        </span>
    </div>
<?php ActiveForm::end()?>
</div>
</body>
