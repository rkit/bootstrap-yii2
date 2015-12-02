<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

$this->title = Yii::t('app', 'Roles');
?>
<?= $this->render('/shared/forms/header', ['title' => $model->description, 'model' => $model]) ?>

<?php $form = ActiveForm::begin(['options' => ['class' => 'ajax-form']]); ?>

    <!-- name -->
  <?= $form->field($model, 'name')->textInput(['maxlength' => true])->hint(Yii::t('app', 'Only latin letters')) ?>

  <!-- description -->
  <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

  <?php if ($model->isSuperUser()):?>
  <div class="alert alert-warning" role="alert">
      <?= Yii::t('app', 'This role has all privileges by default, it can not be deleted') ?>
  </div>
  <?php endif?>

  <!-- permissions -->
  <?= $form->field($model, 'permissions')
      ->dropDownList(ArrayHelper::map($permissions, 'name', function ($row) {
          return Yii::t('app', $row->description);
      }), [
          'multiple' => true,
          'size' => 15,
          'disabled' => $model->isSuperUser()
      ]) ?>

  <!-- roles -->
  <?= $form->field($model, 'roles')
      ->dropDownList(ArrayHelper::map($roles, 'name', function ($row) {
          return Yii::t('app', $row->description);
      }), [
          'multiple' => true,
          'size' => 15,
          'disabled' => $model->isSuperUser()
      ])
      ->hint(Yii::t('app', 'The role will receive all the privileges of the selected role')) ?>

  <hr>
  <div class="form-controls">
    <div class="form-group pull-left">
    	<?= $this->render('/shared/forms/controls', ['model' => $model]) ?>
    </div>

    <?= $this->render('/shared/forms/model-info', ['model' => $model]) ?>
  </div>

<?php ActiveForm::end(); ?>
