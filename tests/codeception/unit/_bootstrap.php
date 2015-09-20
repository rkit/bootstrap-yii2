<?php

$config = require dirname(__DIR__) . '/config/unit.php';
new yii\web\Application($config);

Yii::$app->getDb()->createCommand()->delete('user')->execute();
