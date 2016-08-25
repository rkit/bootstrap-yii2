<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use kartik\date\DatePicker;

$title = $model->user->username ?: $model->user->email;
$this->title = Yii::t('app', 'Users') . ' / ';
$this->title .= !empty($title) ? $title : Yii::t('app', 'Create');
?>
<?= Html::a(Yii::t('app', 'List'), ['index'], ['class' => 'btn btn-default']) ?>&nbsp;
<?= Html::a(Yii::t('app', 'Add'), ['edit'], ['class' => 'btn btn-default']) ?><hr>
<?= $this->render('/shared/flash') ?>

<ul class="nav nav-tabs">
  <li><?= Html::a(Yii::t('app', 'Main information'), ['edit', 'id' => $model->user_id]) ?></li>
  <li class="active"><?= Html::a(Yii::t('app', 'Profile'), ['profile', 'id' => $model->user_id]) ?></li>
</ul><br>

<?php $form = ActiveForm::begin(['options' => ['id' => 'profile-form', 'class' => 'ajax-form']]); ?>

  <div class="row">
    <div class="col-md-8">
      <!-- full_name -->
      <?= $form->field($model, 'full_name')->textInput(['maxlength' => true]) ?>

      <!-- birth_day -->
      <?php $model->birth_day = $model->birth_day > 0 ? $model->birth_day : '' ?>
      <?= $form->field($model, 'birth_day')->widget(DatePicker::classname(), [
          'pluginOptions' => [
              'autoclose' => true,
              'format' => 'yyyy-mm-dd'
          ]
      ]); ?>
    </div>
    <div class="col-md-4">
      <!-- photo -->
      <?= $this->render('/shared/files/image/input', [
          'form' => $form,
          'model' => $model,
          'attribute' => 'photo'
      ]) ?>
    </div>
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
              'data-confirm' => Yii::t('app', 'Are you sure you want to delete this record?')
          ]
      ); ?>
      <?php endif?>
    </div>

    <div class="form-inline pull-right text-right text-muted small">
      <div class="form-group">
        <?= Yii::t('app', 'Date create') ?><br>
        <?= Yii::$app->formatter->asDatetime($model->user->date_create, 'short') ?>
      </div>&nbsp;&nbsp;&nbsp;
      <div class="form-group">
        <?= Yii::t('app', 'Date update') ?><br>
        <?= Yii::$app->formatter->asDatetime($model->user->date_update, 'short') ?>
      </div>
    </div>
  </div>

<?php ActiveForm::end(); ?>
