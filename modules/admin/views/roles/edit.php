<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = Yii::t('app', 'Roles') . ' / ';
$this->title .= $model->name ? Yii::t('app', 'Edit') : Yii::t('app', 'Create');
?>
<?= Html::a(Yii::t('app', 'List'), ['index'], ['class' => 'btn btn-default']) ?>&nbsp;
<?php if ($model->name):?>
<?= Html::a(Yii::t('app', 'Create'), ['edit'], ['class' => 'btn btn-default']) ?>&nbsp;
<?php endif?>
<hr>
<?= $this->render('/shared/flash') ?>

<?php $form = ActiveForm::begin(['options' => ['class' => 'ajax-form']]); ?>

    <!-- name -->
  <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

  <!-- description -->
  <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

  <?php if ($model->model()->isSuperUser()):?>
  <div class="alert alert-warning" role="alert">
      <?= Yii::t('app', 'This role has all privileges by default, it can not be deleted') ?>
  </div>
  <?php endif?>

  <!-- permissions -->
  <?= $form->field($model, 'permissions')->dropDownList($model->permissionsList(), [
          'multiple' => true,
          'size' => 15,
          'disabled' => $model->model()->isSuperUser()
      ]) ?>

  <!-- roles -->
  <?= $form->field($model, 'roles')->dropDownList($model->rolesList(), [
          'multiple' => true,
          'size' => 15,
          'disabled' => $model->model()->isSuperUser()
      ]) ?>

  <hr>
  <div class="form-controls">
    <div class="form-group pull-left">
      <?= Html::submitButton(Yii::t('app', 'Save'), [
          'name' => 'submit',
          'class' => 'btn btn-info',
          'data-loading-text' => Yii::t('app', 'Please wait…')
      ]) ?>
      <?php if ($model->name) : ?>
      <?= Html::a(
          Yii::t('app', 'Delete'),
          ['delete', 'id' => $model->name, 'reload' => true],
          [
              'title' => Yii::t('app', 'Delete'),
              'class' => 'btn btn-danger',
              'data-confirm' => Yii::t('app', 'Are you sure you want to delete this role?')
          ]
      ); ?>
      <?php endif?>
    </div>

    <div class="form-inline pull-right text-right text-muted small">
      <div class="form-group">
        <?= Yii::t('app', 'Date create') ?><br>
        <?= Yii::$app->formatter->asDatetime($model->created_at, 'short') ?>
      </div>&nbsp;&nbsp;&nbsp;
      <div class="form-group">
        <?= Yii::t('app', 'Date update') ?><br>
        <?= Yii::$app->formatter->asDatetime($model->updated_at, 'short') ?>
      </div>
    </div>
  </div>

<?php ActiveForm::end(); ?>
