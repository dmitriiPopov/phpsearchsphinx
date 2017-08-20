<?php

//SHOUD SET IT TO PHP.INI FILE
//ini_set('upload_max_filesize', '1024M');
//ini_set('post_max_size', '1024M');

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'prod');

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = \yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/../config/common.php'),
    require(__DIR__ . '/../config/local/local.common.php'),
    require(__DIR__ . '/../config/web.php')
);

(new yii\web\Application($config))->run();
