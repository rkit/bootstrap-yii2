<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\grid\CheckboxColumn;
use yii\grid\GridView;
use kartik\widgets\DatePicker;

$this->title = Yii::t('app', 'News');
?>
<?= Html::a(Yii::t('app', 'Add'), Url::toRoute('edit'), ['class' => 'btn btn-default']) ?>

<?= Html::beginForm(Url::toRoute('operations'), 'post') ?>

<?php \yii\widgets\Pjax::begin(); ?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel'  => $newsSearch,
    'options' => ['class' => 'gridview'],
    'layout' => Yii::$app->getModule('admin')->defaultGridTemplate($dataProvider, ['delete', 'activate', 'block']),
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
            'contentOptions' => ['style' => 'width: 400px'],
            'value' => function ($model) {
                return Html::a($model['title'], ['edit', 'id' => $model['id']]);
            }
        ],
            // type
        [
            'attribute' => 'typeId',
            'value' => 'type.title',
            'filter' => Html::activeDropDownList(
                $newsSearch, 
                'typeId', 
                ArrayHelper::map($types, 'id', 'title'),
                ['class' => 'form-control', 'prompt' => Yii::t('app', 'All types')]
            )
            
        ],
            // datePub
        [
            'attribute' => 'datePub',
            'format' => 'datetime',
            'filter' => DatePicker::widget(
                [
                    'model' => $newsSearch,
                    'attribute' => 'datePub',
                    'pluginOptions' => [
                    	'autoclose' => true,
                    	'format' => 'yyyy-mm-dd',
                    ],
                    'options' => [
                        'class' => 'form-control',
                    ],
                ]
            )
        ],
            // status
        [
            'attribute' => 'status',
            'format' => 'raw',
            'value' => function ($model) {
                $class = $model->status === $model::STATUS_ACTIVE ? 'success' : 'warning';
                return '<span class="label label-' . $class . '">' . $model->getStatusName() . '</span>';
            },
            'filter' => Html::activeDropDownList(
                $newsSearch,
                'status',
                $statuses,
                ['class' => 'form-control', 'prompt' => Yii::t('app', 'All statuses')]
            )
        ],
            // action buttons
        [
            'class' => 'yii\grid\ActionColumn',
            'contentOptions' => ['class' => 'text-right'],
            'template'=>'{status} {delete}',
            'contentOptions' => ['style' => 'width: 70px'],
            'buttons' => Yii::$app->getModule('admin')->defaultGridButtons()
        ],
    ],
]) ?>

<?php \yii\widgets\Pjax::end(); ?>

<?= Html::endForm(); ?>