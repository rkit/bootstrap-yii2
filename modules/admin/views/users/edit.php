<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = Yii::t('app', 'Users') . ' / ' . ($model->id ?? Yii::t('app', 'Create'));
?>
<?= Html::a(Yii::t('app', 'List'), ['index'], ['class' => 'btn btn-default']) ?>&nbsp;
<?= Html::a(Yii::t('app', 'Add'), ['edit'], ['class' => 'btn btn-default']) ?>&nbsp;
<?php if ($model->id):?>
<!-- <?= Html::a(
    Yii::t('app', 'View on site'),
    ['/users/view', 'id' => $model->id],
    ['class' => 'btn btn-success', 'target' => '_blank']
)?> -->
<?php endif?>
<hr>
<?= $this->render('/shared/flash') ?>

<?php if ($model->id): ?>
<ul class="nav nav-tabs">
  <li class="active"><?= Html::a(Yii::t('app', 'Main information'), ['edit', 'id' => $model->id]) ?></li>
  <li><?= Html::a(Yii::t('app', 'Profile'), ['profile', 'id' => $model->id]) ?></li>
</ul><br>
<?php endif ?>

<?php $form = ActiveForm::begin(['options' => ['class' => 'ajax-form']]); ?>

  <div class="row">
    <div class="col-md-<?= $model->id ? '8' : '12' ?>">

      <!-- role -->
      <?= $form->field($model, 'role_name')->dropDownList($model->rolesList(), [
              'prompt' => Yii::t('app', 'No role')
          ])
          ->label(Html::a(Yii::t('app', 'Role'), ['/admin/roles'])); ?>

      <!-- email -->
      <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

      <!-- passwordNew -->
      <?= $form->field($model, 'passwordNew')->passwordInput([
          'maxlength' => true,
          'autocomplete' => 'new-password'
      ]) ?>

      <!-- status -->
      <?= $form->field($model, 'status')->dropDownList($model->statusesList(), [
              'prompt' => Yii::t('app', 'Select status')
          ]); ?>

    </div>

    <?php if ($model->id) : ?>
    <?= $this->render('info', ['model' => $model]) ?>
    <?php endif?>
  </div>

  <hr>
  <div class="form-controls">
    <div class="form-group pull-left">
      <?= Html::submitButton(Yii::t('app', 'Save'), [
          'name' => 'submit',
          'class' => 'btn btn-info',
          'data-loading-text' => Yii::t('app', 'Please wait…')
      ]) ?>
      <?php if ($model->id) : ?>
      <?= Html::a(
          Yii::t('app', 'Delete'),
          ['delete', 'id' => $model->id, 'reload' => true],
          [
              'title' => Yii::t('app', 'Delete'),
              'class' => 'btn btn-danger',
              'data-confirm' => Yii::t('app', 'Are you sure you want to delete this user?')
          ]
      ); ?>
      <?php endif?>
    </div>

    <div class="form-inline pull-right text-right text-muted small">
      <div class="form-group">
        <?= Yii::t('app', 'Date create') ?><br>
        <?= Yii::$app->formatter->asDatetime($model->date_create, 'short') ?>
      </div>&nbsp;&nbsp;&nbsp;
      <div class="form-group">
        <?= Yii::t('app', 'Date update') ?><br>
        <?= Yii::$app->formatter->asDatetime($model->date_update, 'short') ?>
      </div>
    </div>
  </div>

<?php ActiveForm::end(); ?>
