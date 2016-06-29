<?php
use yii\helpers\Html;

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['/reset-password', 'token' => $user->password_reset_token]);
?>

<?= Yii::t('app', 'Hello') ?>,
<?= Yii::t('app.messages', 'Follow the link below to reset your password') ?>:

<?= Html::a(Html::encode($resetLink), $resetLink); ?>
