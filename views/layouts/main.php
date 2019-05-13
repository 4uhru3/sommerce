<?php

/* @var $this \yii\web\View */

/* @var $content string */

use yii\helpers\Html;
use app\assets\AppAsset;
use yii\helpers\Url;

AppAsset::register($this);
$session = Yii::$app->session;
$this->beginPage();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<header>
    <nav class="navbar navbar-fixed-top navbar-default">
        <nav class="navbar navbar-fixed-top navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                            data-target="#bs-navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <div class="collapse navbar-collapse" id="bs-navbar-collapse">
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="<?= URL::to(['index']) ?>">Orders</a></li>
                        <li class="<?php if ($session->get('lang') == 'ru') {
                            echo('active');
                        } ?>"><a href="<?= URL::to(['index', 'lang' => 'ru']) ?>">RUS</a></li>
                        <li class="<?php if ($session->get('lang') == 'en') {
                            echo('active');
                        } ?>"><a href="<?= URL::to(['index', 'lang' => 'en']) ?>">ENG</a></li>
                </div>
                </li>
                </ul>

            </div>
            </div>
        </nav>
</header>
<div>
    <?= $content ?>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
