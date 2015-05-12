<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\CheckboxColumn;
use yii\grid\GridView;
use yii\web\JsExpression;
use kartik\select2\Select2;

$this->title = Yii::t('app', 'Regions');
?>
<?= Html::a(Yii::t('app', 'Add'), Url::toRoute('edit'), ['class' => 'btn btn-default']) ?>

<?= Html::beginForm(Url::toRoute('operations'), 'post') ?>

<?php \yii\widgets\Pjax::begin(); ?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel'  => $regionSearch,
    'options' => ['class' => 'gridview'],
    'layout' => Yii::$app->getModule('admin')->defaultGridTemplate($dataProvider, ['delete']),
    'tableOptions' => ['class' => 'table ' . ($dataProvider->count ? 'table-hover' : '')],
    'columns' => [
            // checkbox
        [
            'class' => CheckboxColumn::classname(),
            'headerOptions' => ['style' => 'width: 30px']
        ],
            // title
        [
            'attribute' => 'title',
            'format' => 'raw',
            'value' => function ($model) {
                return Html::a(Html::encode($model['title']), ['edit', 'id' => $model['region_id']]);
            }
        ],
            // country_id
        [
            'attribute' => 'country_id',
            'value' => 'country.title',
            'filter' => Select2::widget([
                'model' => $regionSearch,
                'attribute' => 'country_id',
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
                        var data = {id: element.val(), text: "'.@Html::encode($regionSearch->country->title).'"};
                        callback(data);
                    }')
                ]
            ])
        ],
            // action buttons
        [
            'class' => 'yii\grid\ActionColumn',
            'headerOptions' => ['class' => 'text-right', 'style' => 'width: 40px'],
            'template' => '{delete}',
            'buttons' => Yii::$app->getModule('admin')->defaultGridButtons(['delete'])
        ],
    ],
]) ?>

<?php \yii\widgets\Pjax::end(); ?>

<?= Html::endForm(); ?>
