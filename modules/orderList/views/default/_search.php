<?php

use yii\widgets\ActiveForm;

?>
<body>
<form class="form-inline">
    <div class="input-group">
        <?php $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
        ]); ?>
        <input type="text" class="form-control" name="searchValue"
               placeholder="<?= Yii::t('app', 'Search text')?>">
        <input type="hidden" name="status" value="<?=$params['status']?>">
        <span class="input-group-btn search-select-wrap">
                    <select class="form-control search-select" name="searchColumn">
                        <option value="id" selected=""><?= Yii::t('app', 'Order ID')?></option>
                        <option value="link"><?= Yii::t('app', 'Link')?></option>
                        <option value="user"><?= Yii::t('app', 'Username')?></option>
                    </select>
            <button type="submit" class="btn btn-default">
                <span class="glyphicon glyphicon-search"  aria-hidden="true"></span>
            </button>
        </span>
        <?php ActiveForm::end()?>
    </div>
</form>
</body>