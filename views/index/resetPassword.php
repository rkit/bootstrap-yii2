<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('app', 'Reset password');
?>
<div class="site-reset-password">
  <p class="lead"><?= Html::encode($this->title) ?></p>
  <hr>

  <p><?= Yii::t('app.messages', 'Please choose your new password') ?>:</p>

  <div class="row">
    <div class="col-lg-5">
      <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>
        <!-- password -->
        <?= $form->field($model, 'password')->passwordInput() ?>
        <div class="form-group">
          <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-info']) ?>
        </div>
      <?php ActiveForm::end(); ?>
    </div>
  </div>
</div>
