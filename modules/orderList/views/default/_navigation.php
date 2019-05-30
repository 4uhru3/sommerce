<?php

use yii\helpers\Url;

?>
<?php foreach ($orders->getStatusLabel() as $key => $value): ?>
         <?php if ($params['status'] == $key):
             $cssClass = 'active';
         else: $cssClass = null;
         endif; ?>
<li class =<?=$cssClass?>>
    <a href=<?=Url::to(['index', 'status' => $key])?>>
        <?=$orders->getStatusName($key)?>
    </a>
</li>
<?php endforeach; ?>