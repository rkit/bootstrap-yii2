<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<hr>
<div class="form-controls">
    <div class="form-group pull-left">
        <?= Html::submitButton(Yii::t('app', 'Save'), [
            'name' => 'submit',
            'class' => 'btn btn-info',
            'data-loading-text' => Yii::t('app', 'Please wait…')
        ]) ?>
    </div>

    <?php if (isset($model->date_create) && isset($model->date_update)): ?>
    <div class="form-inline pull-right text-right text-muted small">
        <div class="form-group">
            <?= Yii::t('app', 'Date create') ?><br>
            <?= Yii::$app->formatter->asDatetime($model->date_create, 'short') ?>
        </div>&nbsp;&nbsp;&nbsp;
        <div class="form-group">
            <?= Yii::t('app', 'Date update') ?><br>
            <?= Yii::$app->formatter->asDatetime($model->date_update, 'short') ?>
        </div>
    </div>
    <?php endif?>
</div>
<?php ActiveForm::end(); ?>
&nbsp;
<?php if (isset($model->primaryKey) && $model->primaryKey): ?>
<?= Html::a(Yii::t('app', 'Delete'),
  ['delete', 'id' => $model->primaryKey, 'reload' => true],
  [
    'title' => Yii::t('app', 'Delete'),
    'class' => 'btn btn-danger',
    'data-pjax' => '1',
    'data-method' => 'post',
    'data-confirm' => Yii::t('app', 'Are you sure you want to delete this record?')
  ]
); ?>
<?php endif?>
