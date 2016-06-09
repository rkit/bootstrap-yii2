<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

$title = $model->username ?: $model->email;
$this->title = Yii::t('app', 'Users') . ' / ';
$this->title .= !empty($title) ? $title : Yii::t('app', 'Create');
?>
<?= Html::a(Yii::t('app', 'List'), ['index'], ['class' => 'btn btn-default']) ?>&nbsp;
<?= Html::a(Yii::t('app', 'Add'), ['edit'], ['class' => 'btn btn-default']) ?><hr>
<?= $this->render('/shared/flash') ?>

<?php if (!$model->isNewRecord): ?>
<ul class="nav nav-tabs">
  <li class="active"><?= Html::a(Yii::t('app', 'Main information'), ['edit', 'id' => $model->id]) ?></li>
  <li><?= Html::a(Yii::t('app', 'Profile'), ['profile', 'id' => $model->id]) ?></li>
</ul><br>
<?php endif ?>

<?php $form = ActiveForm::begin(['options' => ['class' => 'ajax-form']]); ?>

  <div class="row">
    <div class="col-md-<?= $model->isNewRecord ? '12' : '8' ?>">

      <!-- role -->
      <?= $form->field($model, 'role')
          ->dropDownList(ArrayHelper::map($roles, 'name', 'description'), [
              'class' => 'form-control',
              'prompt' => Yii::t('app', 'No role')
          ])
          ->label(Html::a(Yii::t('app', 'Role'), '/admin/roles')); ?>

      <!-- username -->
      <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

      <!-- email -->
      <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

      <!-- passwordNew -->
      <input type="password" name="password" id="password_" style="display: none">
      <?= $form->field($model, 'passwordNew')->passwordInput(['maxlength' => true]) ?>

      <!-- status -->
      <?= $form->field($model, 'status')
          ->dropDownList($model->getStatuses(), [
              'class' => 'form-control',
              'prompt' => Yii::t('app', 'Select status')
          ]
      ); ?>

    </div>

    <?php if (!$model->isNewRecord) : ?>
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
      <?php if ($model->primaryKey) : ?>
      <?= Html::a(
          Yii::t('app', 'Delete'),
          ['delete', 'id' => $model->primaryKey, 'reload' => true],
          [
              'title' => Yii::t('app', 'Delete'),
              'class' => 'btn btn-danger',
              'data-pjax' => 1,
              'data-method' => 'post',
              'data-confirm' => Yii::t('app', 'Are you sure you want to delete this record?')
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
