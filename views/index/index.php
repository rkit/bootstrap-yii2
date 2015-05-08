<?php
use yii\helpers\Html;
?>
<?php if (!Yii::$app->user->isGuest && !Yii::$app->user->identity->isConfirmed()): ?>
<div class="alert alert-warning" role="alert">
    <?= Yii::t('app', 'To complete the registration process, you must activate your account') ?><br>
    <?= Yii::t('app', 'We sent you a letter on {email}', ['email' => Yii::$app->user->identity->email]) ?><br>
    <?= Html::a(Yii::t('app', 'Send again'), '/confirm-again') ?>
</div>
<?php endif?>

<?php $this->title = Yii::t('app', 'Index'); ?>
<div class="page-header">
    <h1>Bootstrap for Yii2</h1>
</div>

<p class="lead">Advanced Application Template</p>
<ul>
    <li>Users: <span class="text-muted">Roles / Registration / Basic and social authorization</span></li>
    <li>Files: <span class="text-muted">Upload / Crop / Gallery</span></li>
    <li>Geo: <span class="text-muted">Countries / Regions / Cities</span></li>
    <li>Tags</li>
    <li>Settings</li>
    <li>Webpack for assets</li>
</ul>

<hr>
<a href="https://github.com/rkit/bootstrap-yii2" class="btn btn-info">GitHub</a>
<a href="/admin" class="btn btn-default">Control Panel</a>
