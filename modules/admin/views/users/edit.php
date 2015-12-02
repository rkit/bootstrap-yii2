<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

$this->title = Yii::t('app', 'Users');
?>
<?= $this->render('/shared/forms/header', ['title' => $model->username ?: $model->email, 'model' => $model]) ?>

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
          ->label(Html::a(Yii::t('app', 'Role'), Url::toRoute('/admin/roles'))); ?>

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
    	<?= $this->render('/shared/forms/controls', ['model' => $model]) ?>
    </div>

    <?= $this->render('/shared/forms/model-info', ['model' => $model]) ?>
  </div>

<?php ActiveForm::end(); ?>
