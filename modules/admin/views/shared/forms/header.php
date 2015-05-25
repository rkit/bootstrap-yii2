<?php
use yii\helpers\Html;
use yii\helpers\Url;

if (isset($model)) {
    $this->title .= ' / ' . (!empty($title) ? $title : Yii::t('app', 'Create'));
}
?>
<?php if (!isset($hideButtons) || !$hideButtons):?>
<?= Html::a(Yii::t('app', 'List'), Url::toRoute('index'), ['class' => 'btn btn-default']) ?>&nbsp;
<?= Html::a(Yii::t('app', 'Add'), Url::toRoute('edit'), ['class' => 'btn btn-default']) ?>
<hr>
<?php endif?>

<?php if(Yii::$app->session->hasFlash('success')): ?>
<div class="alert alert-success auto-remove animated fadeInDown">
  <span class="glyphicon glyphicon-ok"></span>
  <?= Yii::$app->session->getFlash('success') ?>
</div>
<?php endif?>
