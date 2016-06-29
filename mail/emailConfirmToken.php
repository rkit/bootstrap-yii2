<?php // @codingStandardsIgnoreFile
use yii\helpers\Html;

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['/confirm-email', 'token' => $user->email_confirm_token]);
?>

<?= Yii::t('app', 'Hello') ?>,
<?= Yii::t('app.messages', 'Follow the link below to activate your account') ?>:

<?= Html::a(Html::encode($resetLink), $resetLink) ?>
