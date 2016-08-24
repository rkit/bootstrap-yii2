<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use kartik\select2\Select2;
?>
<?php $model->{$attribute} = $model->getTagValues() ?>
<?= $form->field($model, $attribute)->widget(Select2::classname(), [
    'pluginOptions' => [
        'width' => '100%',
        'multiple' => true,
        'minimumInputLength' => 2,
        'maximumSelectionSize' => 30,
        'ajax' => [
            'url'      => Url::toRoute('tags/autocomplete'),
            'dataType' => 'json',
            'type'     => 'POST',
            'data'     => new JsExpression('function (term) { return {term: term}; }'),
            'results'  => new JsExpression('function (data) { return {results: data}; }')
        ],
        'createSearchChoice' => new JsExpression('function (term, data) {
            if ($(data).filter(function () {
                return this.text.localeCompare(term) === 0; }).length === 0) {
                    return {id:term, text:term};
                }
        }'),
        'initSelection' => new JsExpression('function (element, callback) {
            var data = [];
            $(element.val().split(",")).each(function (k, v) {
                data.push({id: $.trim(v), text: $.trim(v)});
            });
            callback(data);
        }')
    ]
])->label(Html::a(Yii::t('app', 'Tags'), ['/admin/tags'])) ?>
