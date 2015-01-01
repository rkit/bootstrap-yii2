<?php
use yii\helpers\Html;
?>

<?php if (in_array('delete', $operations)): ?>
<?= Html::submitButton(Yii::t('app', 'delete'), [
    'name' => 'operation',
    'value' => 'delete',
    'data-confirmation' => Yii::t('app', 'Are you sure you want to delete this records?'),
    'data-loading-text' => Yii::t('app', 'Please wait…'),
	'class' => 'submit disabled confirmation btn btn-danger btn-xs'
]) ?>
<?php endif ?>

<?php if (in_array('activate', $operations)): ?>
<?= Html::submitButton(Yii::t('app', 'enable'), [
    'name' => 'operation',
    'value' => 'activate',
    'data-loading-text' => Yii::t('app', 'Please wait…'),
	'class' => 'submit disabled btn btn-success btn-xs'
]) ?>
<?php endif ?>

<?php if (in_array('block', $operations)): ?>
<?= Html::submitButton(Yii::t('app', 'disable'), [
    'name' => 'operation',
    'value' => 'block',
    'data-loading-text' => Yii::t('app', 'Please wait…'),
	'class' => 'submit disabled btn btn-warning btn-xs'
]) ?>
<?php endif ?>