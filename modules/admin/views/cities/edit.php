<?php
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\select2\Select2;

$this->title = Yii::t('app', 'Cities');
?>
<?= $this->render('/shared/forms/header', ['title' => $model->title, 'model' => $model]) ?>

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
              'url'      => Url::toRoute('suggestions/countries'),
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
              'url'      => Url::toRoute('suggestions/regions'),
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
    	<?= $this->render('/shared/forms/controls', ['model' => $model]) ?>
    </div>
  </div>

<?php ActiveForm::end(); ?>
