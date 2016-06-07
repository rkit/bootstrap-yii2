<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<?php if (Yii::$app->session->hasFlash('success')) : ?>
<div class="alert alert-success auto-remove animated fadeInDown">
  <span class="glyphicon glyphicon-ok"></span>
  <?= Yii::$app->session->getFlash('success') ?>
</div>
<?php endif?>
