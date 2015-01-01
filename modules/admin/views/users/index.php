<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\grid\CheckboxColumn;
use yii\grid\GridView;
use kartik\widgets\DatePicker;

$this->title = Yii::t('app', 'Users');
?>
<?= Html::a(Yii::t('app', 'Add'), Url::toRoute('edit'), ['class' => 'btn btn-default']) ?>

<?= Html::beginForm(Url::toRoute('operations'), 'post') ?>

<?php \yii\widgets\Pjax::begin(); ?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel'  => $userSearch,
    'options' => ['class' => 'gridview'],
    'layout' => Yii::$app->getModule('admin')->defaultGridTemplate($dataProvider, ['delete', 'activate', 'block']),
    'tableOptions' => ['class' => 'table ' . ($dataProvider->count ? 'table-hover' : '')],
    'columns' => [
            // checkbox
        [
            'class' => CheckboxColumn::classname(),
            'contentOptions' => ['style' => 'width: 30px']
        ],
            // username
        [
            'attribute' => 'username',
            'format' => 'raw',
            'value' => function ($model) {
                $username = $model['username'] ? $model['username'] : '(' . Yii::t('app', 'not set') . ')';
                return Html::a($username, ['edit', 'id' => $model['id']]) . 
                (
                    $model['id'] === user()->id 
                    ? ' <span class="label label-info">' . Yii::t('app', 'it`s me') . '</span>' 
                    : ''
                );
            }
        ],
            // email
        [
            'attribute' => 'email',
            'format' => 'raw',
            'value' => function ($model) {
                return Html::a($model['email'], ['edit', 'id' => $model['id']]);
            }
        ],
            // dateCreate
        [
            'attribute' => 'dateCreate',
            'format' => 'raw',
            'contentOptions' => ['style' => 'width: 180px'],
            'filter' => DatePicker::widget(
                [
                    'model' => $userSearch,
                    'attribute' => 'dateCreate',
                    'pluginOptions' => [
                    	'autoclose' => true,
                    	'format' => 'yyyy-mm-dd',
                    ],
                    'options' => [
                        'class' => 'form-control',
                    ],
                ]
            ),
            'value' => function ($model) {
                return 
                    Yii::$app->formatter->asDateTime($model->dateCreate) . '<br>' .
                    '<span class="text-muted small">
                        ' . Yii::t('app', 'Login') . ': ' . 
                        ($model->dateLogin > 0 ? Yii::$app->formatter->asDateTime($model->dateLogin) : '—') .
                    '</span>';
            }
        ],
            // ip
        [
            'attribute' => 'ip',
            'format' => 'text',
            'contentOptions' => ['style' => 'width: 150px'],
            'value' => function ($model) {
                return long2ip($model->ip);
            },
        ],
            // role
        [
            'attribute' => 'role',
            'format' => 'raw',
            'filter' => Html::activeDropDownList(
                $userSearch,
                'role',
                ArrayHelper::map($roles, 'name', 'description'),
                ['class' => 'form-control', 'prompt' => Yii::t('app', 'All roles')]
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
                $userSearch,
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