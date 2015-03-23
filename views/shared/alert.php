<?php
use yii\helpers\Html;
$email = Html::mailto(Yii::t('app', 'contact us'), Yii::$app->settings->emailMain);
?>
<?= $message; ?>
<p class="text-muted small">
    <?= Yii::t('app', 'If you need help, please {email}', ['email' => $email]) ?>
</p>