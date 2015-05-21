<?php
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */
$this->title = Yii::t('app', 'Error');
$email = Html::mailto(Yii::t('app', 'contact us'), Yii::$app->settings->emailMain);
?>
<div class="site-error">
  <?php if (isset($exception->statusCode) && $exception->statusCode === 404):?>
  <h3><?= Yii::t('app', 'Page not found') ?></h3>
  <?php else:?>
  <div class="alert alert-danger">
      <?= nl2br(Html::encode($message)) ?>
  </div>
  <?php endif?>

  <p class="text-muted">
      <?= Yii::t('app', 'Please {email} and we will help', ['email' => $email]) ?>
  </p>
</div>
