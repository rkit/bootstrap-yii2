<?php $this->title = Yii::t('app', 'Welcome!') ?>
<p class="lead">
  <span class="label label-info">
    <?= Yii::t('app', 'You') ?>
    «<?= Yii::t('app', Yii::$app->authManager->getRole(Yii::$app->user->identity->role)->description) ?>»
  </span>
</p>

<?php if (YII_ENV_DEV) :?>
<hr>
<p class="lead">
  <span class="label label-danger">
    <?= Yii::t('app', 'Debug mode is enabled') ?>
  </span>
</p>
<?php endif?>

<hr>
<div class="text-muted small">
  <h6><?= Yii::t('app', 'Information about the system') ?></h6>
  <ul>
    <li><?= Yii::t('app', 'Time zone') ?> <?= Yii::$app->timeZone ?></li>
    <li>Yii <?= Yii::getVersion() ?></li>
    <li>PHP <?= phpversion() ?> (<?= strtoupper(php_sapi_name()) ?>)</li>
    <?php if (isset($_SERVER['SERVER_SOFTWARE'])) :?>
    <li>Server <?= ucfirst($_SERVER['SERVER_SOFTWARE']); ?></li>
    <?php endif?>
  </ul>
</div>
