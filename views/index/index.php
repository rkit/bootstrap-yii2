<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<?php if (!Yii::$app->user->isGuest && !Yii::$app->user->identity->isConfirmed()): ?>
<div class="alert alert-warning" role="alert">
  <?= Yii::t('app.msg', 'To complete the registration process, you must activate your account') ?><br>
  <?= Yii::t('app.msg', 'We sent you a letter on {email}', ['email' => Yii::$app->user->identity->email]) ?><br>
  <?= Html::a(Yii::t('app', 'Send again'), ['/confirm-request']) ?>
</div>
<?php endif?>

<?php $this->title = Yii::t('app', 'Index'); ?>
<div class="page-header">
  <h1>Bootstrap for Yii2</h1>
</div>

<p class="lead">Features</p>
<ul>
  <li>Users, Roles, Registration, Basic and social authorization</li>
  <li>Settings</li>
  <li><a href="https://github.com/rkit/filemanager-yii2">File Manager</a></li>
  <li><a href="https://webpack.github.io/">Webpack for assets</a></li>
</ul>

<hr>
<a href="https://github.com/rkit/bootstrap-yii2" class="btn btn-info">GitHub</a>
<?= Html::a(Yii::t('app', 'Control Panel'), ['/admin'], ['class' => 'btn btn-default']) ?>
