<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\grid\CheckboxColumn;
use yii\grid\GridView;
use kartik\date\DatePicker;

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
              'headerOptions' => ['style' => 'width: 30px']
          ],
              // username
          [
              'attribute' => 'username',
              'format' => 'raw',
              'value' => function ($model) {
                  $username = $model['username'] ? $model['username'] : '(' . Yii::t('app', 'not set') . ')';
                  return Html::a(Html::encode($username), ['edit', 'id' => $model['id']]) .
                  (
                      $model['id'] === Yii::$app->user->id
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
                  $email = $model['email'] ? $model['email'] : '(' . Yii::t('app', 'not set') . ')';
                  return Html::a(Html::encode($email), ['edit', 'id' => $model['id']]);
              }
          ],
              // date_create
          [
              'attribute' => 'date_create',
              'format' => 'raw',
              'headerOptions' => ['style' => 'width: 180px'],
              'filter' => DatePicker::widget(
                  [
                      'model' => $userSearch,
                      'attribute' => 'date_create',
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
                      Yii::$app->formatter->asDateTime($model->date_create) . '<br>' .
                      '<span class="text-muted small">
                          ' . Yii::t('app', 'Login') . ': ' .
                          ($model->date_login > 0 ? Yii::$app->formatter->asDateTime($model->date_login) : '—') .
                      '</span>';
              }
          ],
              // ip
          [
              'attribute' => 'ip',
              'format' => 'text',
              'headerOptions' => ['style' => 'width: 150px'],
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
              ),
              'value' => function ($model) {
                  return $model->roles ? $model->roles->description : '—';
              },
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
              'headerOptions' => ['class' => 'text-right', 'style' => 'width: 70px'],
              'template' => '{status} {delete}',
              'buttons' => Yii::$app->getModule('admin')->defaultGridButtons()
          ],
      ],
  ]) ?>

  <?php \yii\widgets\Pjax::end(); ?>
<?= Html::endForm(); ?>
