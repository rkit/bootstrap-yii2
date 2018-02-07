<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<?php if (!Yii::$app->user->isGuest && !Yii::$app->user->identity->isConfirmed()): ?>
<div class="alert alert-warning" role="alert">
  <?= Yii::t('app.msg', 'To complete the registration process, you must activate your account') ?><br>
  <?= Yii::t('app.msg', 'We sent you a letter on {email}', ['email' => Yii::$app->user->identity->email]) ?><br>
  <?= Html::a(Yii::t('app', 'Send again'), ['auth/confirm-request']) ?>
</div>
<?php endif?>

<?php $this->title = Yii::t('app', 'Index'); ?>
<div class="page-header">
  <h1><?= Yii::$app->name?></h1>
</div>

<?= Html::a(Yii::t('app', 'Control Panel'), ['/admin'], ['class' => 'btn btn-info']) ?>