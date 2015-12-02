<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = Yii::t('app', 'Tags');
?>
<?= $this->render('/shared/forms/header', ['title' => $model->title, 'model' => $model]) ?>

<?php $form = ActiveForm::begin(['options' => ['class' => 'ajax-form']]); ?>

  <!-- title -->
  <?= $form->field($model, 'title')->textInput() ?>

  <!-- count -->
  <?= $form->field($model, 'count') ?>

  <hr>
  <div class="form-controls">
    <div class="form-group pull-left">
    	<?= $this->render('/shared/forms/controls', ['model' => $model]) ?>
    </div>

    <?= $this->render('/shared/forms/model-info', ['model' => $model]) ?>
  </div>

<?php ActiveForm::end(); ?>
