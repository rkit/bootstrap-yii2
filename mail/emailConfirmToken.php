<?php
use yii\helpers\Html;

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['/confirm-email', 'token' => $user->emailConfirmToken]);
?>

<?= Yii::t('app', 'Hello') ?>,
<?= Yii::t('app', 'Follow the link below to activate your account') ?>:

<?= Html::a(Html::encode($resetLink), $resetLink) ?>