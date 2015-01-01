<?php
use yii\helpers\Html;

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['/reset-password', 'token' => $user->passwordResetToken]);
?>

<?= Yii::t('app', 'Hello') ?>,
<?= Yii::t('app', 'Follow the link below to reset your password') ?>:

<?= Html::a(Html::encode($resetLink), $resetLink); ?>