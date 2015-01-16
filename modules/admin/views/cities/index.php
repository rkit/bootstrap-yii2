<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\CheckboxColumn;
use yii\grid\GridView;
use yii\web\JsExpression;
use kartik\widgets\Select2;

$this->title = Yii::t('app', 'Cities');
?>
<?= Html::a(Yii::t('app', 'Add'), Url::toRoute('edit'), ['class' => 'btn btn-default']) ?>

<?= Html::beginForm(Url::toRoute('operations'), 'post') ?>

<?php \yii\widgets\Pjax::begin(); ?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel'  => $citySearch,
    'options' => ['class' => 'gridview'],
    'layout' => Yii::$app->getModule('admin')->defaultGridTemplate($dataProvider, ['delete']),
    'tableOptions' => ['class' => 'table ' . ($dataProvider->count ? 'table-hover' : '')],
    'columns' => [
            // checkbox
        [
            'class' => CheckboxColumn::classname(),
            'contentOptions' => ['style' => 'width: 30px']
        ],
            // title
        [
            'attribute' => 'title',
            'format' => 'raw',
            'value' => function ($model) {
                return Html::a(e($model['title']), ['edit', 'id' => $model['cityId']]);
            }
        ],
            // countryId
        [
            'attribute' => 'countryId',
            'value' => 'country.title',
            'filter' => Select2::widget([
                'model' => $citySearch, 
                'attribute' => 'countryId',
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
                        var data = {id: element.val(), text: "'.@e($citySearch->country->title).'"};
                        callback(data);
                    }')
                ]
            ])
        ],
            // regionId
        [
            'attribute' => 'regionId',
            'value' => 'region.title',
            'filter' => Select2::widget([
                'model' => $citySearch, 
                'attribute' => 'regionId',
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
                        var data = {id: element.val(), text: "'.@e($citySearch->region->title).'"};
                        callback(data);
                    }')
                ]
            ])
        ],
            // important
        [
            'attribute' => 'important',
            'format' => 'raw',
            'value' => function ($model) {
                if ($model->important) {
                    return '<span class="label label-info">' . Yii::t('app', 'Big city') .'</span>';
                }
            }
        ],
            // action buttons
        [
            'class' => 'yii\grid\ActionColumn',
            'contentOptions' => ['class' => 'text-right'],
            'template'=>'{delete}',
            'buttons' => Yii::$app->getModule('admin')->defaultGridButtons(['delete'])
        ],
    ],
]) ?>

<?php \yii\widgets\Pjax::end(); ?>

<?= Html::endForm(); ?>