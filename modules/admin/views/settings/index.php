<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = Yii::t('app', 'Settings');
?>
<?= $this->render('/shared/flash') ?>

<?php $form = ActiveForm::begin(['options' => ['class' => 'ajax-form']]); ?>

  <h2><small><?= Yii::t('app', 'Email') ?></small></h2>
  <div class="well">
    <!-- emailRequest -->
    <?= $form->field($model, 'emailRequest')->textInput(['maxlength' => true]) ?>
    <!-- emailMain -->
    <?= $form->field($model, 'emailMain')->textInput(['maxlength' => true]) ?>
    <!-- emailName -->
    <?= $form->field($model, 'emailName')->textInput(['maxlength' => true]) ?>
    <!-- emailPrefix -->
    <?= $form->field($model, 'emailPrefix')->textInput(['maxlength' => true]) ?>
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
