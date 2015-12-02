<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = Yii::t('app', 'Settings');
?>
<?= $this->render('/shared/forms/header', ['hideButtons' => true]) ?>

<?php $form = ActiveForm::begin(['options' => ['class' => 'ajax-form']]); ?>

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
    	<?= $this->render('/shared/forms/controls', ['model' => $model]) ?>
    </div>
  </div>

<?php ActiveForm::end(); ?>
