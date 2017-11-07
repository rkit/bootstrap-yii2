<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('app', 'Request password reset');
?>
<div class="site-request-password-reset">
  <p class="lead"><?= Html::encode($this->title) ?></p>
  <hr>

  <p><?= Yii::t('app', 'Please fill out your email. A link to reset password will be sent there') ?>.</p>

  <div class="row">
    <div class="col-lg-5">
      <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>
        <!-- email -->
        <?= $form->field($model, 'email') ?>
        <div class="form-group">
          <?= Html::submitButton(Yii::t('app', 'Send'), ['class' => 'btn btn-info']) ?>
        </div>
      <?php ActiveForm::end(); ?>
    </div>
  </div>
</div>
