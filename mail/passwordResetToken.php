<?php
use yii\helpers\Html;

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['/reset-password', 'token' => $user->password_reset_token]);
?>

<?= Yii::t('app', 'Hello') ?>!<br /><br />
<?= Yii::t('app', 'Follow the link below to reset your password') ?>:<br />
<?= Html::a(Html::encode($resetLink), $resetLink); ?>
