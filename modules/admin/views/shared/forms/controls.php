<?php
use yii\helpers\Html;
?>
<?= Html::submitButton(Yii::t('app', 'Save'), [
    'name' => 'submit',
    'class' => 'btn btn-info',
    'data-loading-text' => Yii::t('app', 'Please wait…')
]) ?>
<?php if (isset($model->primaryKey) && $model->primaryKey): ?>
<?= Html::a(Yii::t('app', 'Delete'),
  ['delete', 'id' => $model->primaryKey, 'reload' => true],
  [
      'title' => Yii::t('app', 'Delete'),
      'class' => 'btn btn-danger',
      'data-pjax' => true,
      'data-method' => 'post',
      'data-confirm' => Yii::t('app', 'Are you sure you want to delete this record?')
  ]
); ?>
<?php endif?>
