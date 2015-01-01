<?php $this->title = Yii::t('app', 'Welcome!') ?>
<p class="lead">
    <span class="label label-info">
        <?= Yii::t('app', 'You') ?>
        «<?= @Yii::$app->authManager->getRole(user()->identity->role)->description ?>»
    </span>
</p>

<?php if (YII_ENV_DEV):?>
<hr>
<p class="lead">
    <span class="label label-danger">
        <?= Yii::t('app', 'Debug mode is enabled') ?>
    </span>
</p>
<?php endif?>

<hr>
<div class="text-muted small">
    <h6><?= Yii::t('app', 'Time zone') ?></h6>
    <ul>
        <li><?= Yii::$app->timeZone ?></li>
    </ul>
    
    <h6><?= Yii::t('app', 'Information about the system') ?></h6>
    <ul>
        <li><?= php_uname('n') ?></li>
        <li><?= php_uname('v') ?></li>
        <li><?= apache_get_version() ?></li>
        <li>PHP <?= phpversion() ?></li>
        <li>MySQL <?= Yii::$app->db->createCommand('SELECT VERSION()')->queryScalar() ?></li>
    </ul>
</div>
