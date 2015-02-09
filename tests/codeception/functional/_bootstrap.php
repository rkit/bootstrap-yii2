<?php

new yii\web\Application(require(dirname(__DIR__) . '/config/functional.php'));

Yii::$app->getDb()->createCommand()->delete('user')->execute();
