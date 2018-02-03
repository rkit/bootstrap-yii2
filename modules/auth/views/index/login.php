<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('app', 'Login');
?>
<div class="site-login">
  <p class="lead"><?= Html::encode($this->title) ?></p>
  <hr>

  <div class="row">
    <div class="col-lg-5">
      <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
        <!-- email -->
        <?= $form->field($model, 'email') ?>
        <!-- pasword -->
        <?= $form->field($model, 'password')->passwordInput() ?>
        <!-- rememberMe -->
        <?= $form->field($model, 'rememberMe')->checkbox() ?>
        <div class="form-group text-muted">
          <?= Html::a(Yii::t('app', 'Forgot your password?'), ['request-password-reset']) ?>
        </div>
        <hr>
        <div class="form-group">
          <div class="pull-left">
            <?= Html::submitButton(Yii::t('app', 'Login'), ['class' => 'btn btn-info', 'name' => 'login-button']) ?>
          </div>
          <div class="pull-left">
            <?= yii\authclient\widgets\AuthChoice::widget([
                'baseAuthUrl' => ['social/index']
            ]) ?>
          </div>
        </div>
      <?php ActiveForm::end(); ?>
    </div>
  </div>
</div>
