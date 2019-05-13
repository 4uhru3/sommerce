<?php

use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">
    <style>
        .label-default{
            border: 1px solid #ddd;
            background: none;
            color: #333;
            min-width: 30px;
            display: inline-block;
        }
    </style>
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<form id="w0" class="form-inline" action="<?= url::to(['index'])  ?>" method="get">
    <input type="hidden" name="r" value="site/index">
    <input type="hidden" name="statusID" value="<?=$statusID?>">
    <div class="input-group">
        <input type="text" class="form-control" name="searchValue"
               placeholder="<?= Yii::t('app', 'Search text')?>">
        <span class="input-group-btn search-select-wrap">
                    <select class="form-control search-select" name="searchColumn">
                        <option value="id" selected="">Order ID</option>
                        <option value="link">Link</option>
                        <option value="user">Username</option>
                    </select>
            <button type="submit" class="btn btn-default">
                <span class="glyphicon glyphicon-search"  aria-hidden="true"></span>
            </button>
        </span>
    </div>
</form>
</body>

