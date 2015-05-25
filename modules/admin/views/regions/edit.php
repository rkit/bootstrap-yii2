<?php
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\select2\Select2;

$this->title = Yii::t('app', 'Regions');
?>
<?= $this->render('/shared/forms/header', ['title' => $model->title, 'model' => $model]) ?>

<?php $form = ActiveForm::begin(['options' => ['class' => 'form']]); ?>

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

  <!-- title -->
  <?= $form->field($model, 'title')->textInput() ?>

<?= $this->render('/shared/forms/bottom', ['model' => $model]) ?>
