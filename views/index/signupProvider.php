<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\UserProvider;

$this->title = Yii::t('app', 'Signup');
?>
<div class="site-signup">
  <p class="lead">
    <?= Html::encode($this->title) . ' ' .
        Yii::t('app', 'through') . ' ' .
        ucfirst(UserProvider::getTypes()[$model->type]) ?>
  </p>
  <hr>

  <div class="row">
    <div class="col-lg-5">
      <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
        <p><?= Yii::t('app.messages', 'To complete the registration enter your email address') ?></p>
        <!-- email -->
        <?= $form->field($model, 'email') ?>
        <hr>
        <div class="form-group">
          <?= Html::submitButton(Yii::t('app', 'Signup'), ['class' => 'btn btn-info', 'name' => 'signup-button']) ?>
        </div>
      <?php ActiveForm::end(); ?>
    </div>
  </div>
</div>
