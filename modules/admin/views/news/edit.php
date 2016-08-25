<?php
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\datetime\DateTimePicker;

$this->title = Yii::t('app', 'News') . ' / ';
$this->title .= !empty($model->title) ? $model->title : Yii::t('app', 'Create');
?>
<?= Html::a(Yii::t('app', 'List'), ['index'], ['class' => 'btn btn-default']) ?>&nbsp;
<?= Html::a(Yii::t('app', 'Add'), ['edit'], ['class' => 'btn btn-default']) ?><hr>
<?= $this->render('/shared/flash') ?>

<?php $form = ActiveForm::begin(['options' => ['id' => 'news-form', 'class' => 'ajax-form']]); ?>

  <!-- title -->
  <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

  <!-- type_id -->
  <?= $form->field($model, 'type_id')
      ->dropDownList(ArrayHelper::map($types, 'id', 'title'), [
          'class' => 'form-control',
          'prompt' => Yii::t('app', 'Select the type')
      ])
      ->label(
        $model->type_id ?
        Html::a($model->getAttributeLabel('type_id'), ['/admin/news-types/edit', 'id' => $model->type_id]) :
        Html::a($model->getAttributeLabel('type_id'), '/admin/news-types')
      ); ?>

  <!-- text -->
  <?= $this->render('/shared/editor', [
      'form' => $form, 'model' => $model, 'attribute' => 'text', 'imageUploadUrl' => 'text-upload'
  ]) ?>

  <!-- preview -->
  <?= $this->render('/shared/files/image/input', [
      'form' => $form,
      'model' => $model,
      'attribute' => 'preview',
      'crop' => false
  ]) ?>

  <!-- gallery -->
  <hr>
  <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
    <div class="panel panel-default">
      <div class="panel-heading" role="tab" id="headingOne">
        <h4 class="panel-title">
          <span class="glyphicon glyphicon-picture"></span>
          <a data-toggle="collapse" data-parent="#accordion"
             href="#accordion-<?= Html::getInputId($model, 'gallery') ?>" aria-expanded="true">
            <?= $model->getAttributeLabel('gallery') ?>
          </a>
        </h4>
      </div>
      <div id="accordion-<?= Html::getInputId($model, 'gallery') ?>" class="panel-collapse collapse"
         role="tabpanel" aria-labelledby="headingOne">
        <div class="panel-body">
          <?= $this->render('/shared/files/gallery/input', [
              'form' => $form, 'model' => $model, 'attribute' => 'gallery'
          ]) ?>
        </div>
      </div>
    </div>
  </div>
  <hr>

  <!-- date_pub -->
  <?php $model->date_pub = Yii::$app->formatter->asDatetime($model->date_pub ?: 'now', 'php:Y-m-d H:i:s'); ?>
  <?= $form->field($model, 'date_pub')->widget(DateTimePicker::className(), [
    	'pluginOptions' => [
  		    'autoclose' => true,
  		    'format' => 'yyyy-mm-dd hh:ii:ss'
  	  ]
  ]) ?>

  <!-- tags -->
  <?= $this->render('/shared/tags', ['form' => $form, 'model' => $model, 'attribute' => 'tagValues']) ?>

  <!-- reference -->
  <?= $form->field($model, 'reference')->textInput(['maxlength' => true]) ?>

  <!-- status -->
  <?= $form->field($model, 'status')->checkbox(['label' => Yii::t('app', 'Publish')]) ?>

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
        <?= Yii::$app->formatter->asDatetime($model->date_create, 'short') ?>
      </div>&nbsp;&nbsp;&nbsp;
      <div class="form-group">
        <?= Yii::t('app', 'Date update') ?><br>
        <?= Yii::$app->formatter->asDatetime($model->date_update, 'short') ?>
      </div>
    </div>
  </div>

<?php ActiveForm::end(); ?>
