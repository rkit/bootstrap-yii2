<?php if (Yii::$app->session->hasFlash('success')) : ?>
<div class="alert alert-success auto-remove animated fadeInDown">
  <span class="glyphicon glyphicon-ok"></span>&nbsp;
  <?= Yii::$app->session->getFlash('success') ?>
</div>
<?php endif?>
<?php if (Yii::$app->session->hasFlash('error')) : ?>
<div class="alert alert-warning animated fadeInDown">
  <span class="glyphicon glyphicon-alert"></span>&nbsp;
  <?= Yii::$app->session->getFlash('error') ?>
</div>
<?php endif?>
