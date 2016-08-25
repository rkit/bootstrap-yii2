<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = Yii::t('app', 'Settings');
?>
<?= $this->render('/shared/flash') ?>

<?php $form = ActiveForm::begin(['options' => ['id' => 'settings-form', 'class' => 'ajax-form']]); ?>

  <h2><small><?= Yii::t('app', 'Email') ?></small></h2>
  <div class="well">
    <!-- emailMain -->
    <?= $form->field($model, 'emailMain') ?>
    <!-- emailName -->
    <?= $form->field($model, 'emailName') ?>
    <!-- emailPrefix -->
    <?= $form->field($model, 'emailPrefix') ?>
  </div>

  <hr>
  <div class="form-controls">
    <div class="form-group">
      <?= Html::submitButton(Yii::t('app', 'Save'), [
          'name' => 'submit',
          'class' => 'btn btn-info',
          'data-loading-text' => Yii::t('app', 'Please wait…')
      ]) ?>
    </div>
  </div>

<?php ActiveForm::end(); ?>
