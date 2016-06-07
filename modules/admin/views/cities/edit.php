<?php
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\select2\Select2;

$this->title = Yii::t('app', 'Cities') . ' / ';
$this->title .= !empty($model->title) ? $model->title : Yii::t('app', 'Create');
?>
<?= Html::a(Yii::t('app', 'List'), Url::toRoute('index'), ['class' => 'btn btn-default']) ?>&nbsp;
<?= Html::a(Yii::t('app', 'Add'), Url::toRoute('edit'), ['class' => 'btn btn-default']) ?><hr>
<?= $this->render('/shared/flash') ?>

<?php $form = ActiveForm::begin(['options' => ['class' => 'ajax-form']]); ?>

  <!-- country_id -->
  <?= $form->field($model, 'country_id')->widget(Select2::classname(), [
      'options' => ['placeholder' => ' '],
      'pluginOptions' => [
          'width' => '100%',
          'multiple' => false,
          'allowClear' => true,
          'minimumInputLength' => 2,
          'maximumSelectionSize' => 30,
          'ajax' => [
              'url'      => Url::toRoute('countries/autocomplete'),
              'dataType' => 'json',
              'type'     => 'POST',
              'data'     => new JsExpression('function (term) { return {term: term}; }'),
              'results'  => new JsExpression('function (data) { return {results: data}; }')
          ],
          'initSelection' => new JsExpression('function (element, callback) {
              var data = {id: element.val(), text: "'.@Html::encode($model->country->title).'"};
              callback(data);
          }')
      ]
  ])->label(Html::a($model->getAttributeLabel('country_id'), Url::toRoute('/admin/countries'))) ?>

  <!-- region_id -->
  <?= $form->field($model, 'region_id')->widget(Select2::classname(), [
      'options' => ['placeholder' => ' '],
      'pluginOptions' => [
          'width' => '100%',
          'multiple' => false,
          'allowClear' => true,
          'minimumInputLength' => 2,
          'maximumSelectionSize' => 30,
          'ajax' => [
              'url'      => Url::toRoute('regions/autocomplete'),
              'dataType' => 'json',
              'type'     => 'POST',
              'data'     => new JsExpression('function (term) { return {term: term}; }'),
              'results'  => new JsExpression('function (data) { return {results: data}; }')
          ],
          'initSelection' => new JsExpression('function (element, callback) {
              var data = {id: element.val(), text: "'.@Html::encode($model->region->title).'"};
              callback(data);
          }')
      ]
  ])->label(Html::a($model->getAttributeLabel('region_id'), Url::toRoute('/admin/regions'))) ?>

  <!-- area -->
  <?= $form->field($model, 'area')->textInput() ?>

  <!-- title -->
  <?= $form->field($model, 'title')->textInput() ?>

  <!-- important -->
  <?= $form->field($model, 'important')->checkbox() ?>

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
  </div>

<?php ActiveForm::end(); ?>
