<?php
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\select2\Select2;

$this->title = Yii::t('app', 'Regions') . ' / ';
$this->title .= !empty($model->title) ? $model->title : Yii::t('app', 'Create');
?>
<?= Html::a(Yii::t('app', 'List'), ['index'], ['class' => 'btn btn-default']) ?>&nbsp;
<?= Html::a(Yii::t('app', 'Add'), ['edit'], ['class' => 'btn btn-default']) ?><hr>
<?= $this->render('/shared/flash') ?>

<?php $form = ActiveForm::begin(['options' => ['id' => 'regions-form', 'class' => 'ajax-form']]); ?>

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
  ])->label(
    $model->country ?
    Html::a($model->getAttributeLabel('country_id'), ['/admin/countries/edit', 'id' => $model->country->country_id]) :
    Html::a($model->getAttributeLabel('country_id'), ['/admin/countries'])
  ) ?>

  <!-- title -->
  <?= $form->field($model, 'title')->textInput() ?>

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
  </div>

<?php ActiveForm::end(); ?>
