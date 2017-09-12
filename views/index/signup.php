<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('app', 'Signup');
?>
<div class="site-signup">
  <p class="lead"><?= Html::encode($this->title) ?></p>
  <hr>

  <div class="row">
    <div class="col-lg-5">
      <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
        <!-- fullName -->
        <?= $form->field($model, 'fullName') ?>
        <!-- email -->
        <?= $form->field($model, 'email') ?>
        <!-- password -->
        <?= $form->field($model, 'password')->passwordInput() ?>
        <hr>
        <div class="form-group">
          <div class="pull-left">
            <?= Html::submitButton(Yii::t('app', 'Signup'), ['class' => 'btn btn-info', 'name' => 'signup-button']) ?>
          </div>
          <div class="pull-right">
            <?= yii\authclient\widgets\AuthChoice::widget([
                'baseAuthUrl' => ['auth-social'],
            ]) ?>
          </div>
        </div>
      <?php ActiveForm::end(); ?>
    </div>
  </div>
</div>
