<?php

use yii\helpers\Url;

?>

<body>
<form class="form-inline" action="<?= url::to(['index'])?>" method="get">
    <input type="hidden" name="r" value="<?= url::to(['index'])?>">
    <input type="hidden" name="statusID" value="<?=$statusID?>">
    <div class="input-group">
        <input type="text" class="form-control" name="searchValue"
               placeholder="<?= Yii::t('app', 'Search text')?>">
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
    </div>
</form>
</body>

