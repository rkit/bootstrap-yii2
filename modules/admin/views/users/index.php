<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\CheckboxColumn;
use yii\grid\GridView;
use kartik\date\DatePicker;

$this->title = Yii::t('app', 'Users');
?>
<?= Html::a(Yii::t('app', 'Add'), ['edit'], ['class' => 'btn btn-default']) ?>

<?= Html::beginForm(['batch'], 'post') ?>
  <?php \yii\widgets\Pjax::begin(); ?>

  <?= GridView::widget([
      'dataProvider' => $dataProvider,
      'filterModel'  => $userSearch,
      'options' => ['class' => 'gridview'],
      'layout' =>
      '<div class="panel panel-default">
         <div class="panel-body panel-table">
           <div class="table-responsive">{items}</div>
         </div>
         <div class="panel-footer">{summary}</div>
       </div>
       <div class="batch">
         ' . Html::submitButton(Yii::t('app', 'delete'), [
             'name' => 'operation',
             'value' => 'delete',
             'data-confirmation' => Yii::t('app', 'Are you sure you want to delete this records?'),
             'data-loading-text' => Yii::t('app', 'Please wait…'),
             'class' => 'submit disabled confirmation btn btn-danger btn-xs'
         ]) . '
         ' . Html::submitButton(Yii::t('app', 'active'), [
             'name' => 'operation',
             'value' => 'set-active',
             'data-loading-text' => Yii::t('app', 'Please wait…'),
             'class' => 'submit disabled btn btn-success btn-xs'
         ]) . '
         ' . Html::submitButton(Yii::t('app', 'block'), [
             'name' => 'operation',
             'value' => 'set-block',
             'data-loading-text' => Yii::t('app', 'Please wait…'),
             'class' => 'submit disabled btn btn-warning btn-xs'
         ]) . '
       </div>
      {pager}
      ',
      'tableOptions' => ['class' => 'table ' . ($dataProvider->count ? 'table-hover' : '')],
      'columns' => [
          /**
           * @var id
           */
          [
              'class' => CheckboxColumn::class,
              'headerOptions' => ['style' => 'width: 30px']
          ],
          /**
           * @var username
           */
          [
              'attribute' => 'username',
              'format' => 'raw',
              'value' => function ($model) {
                  $username = $model->username ? $model->username : '(' . Yii::t('app', 'not set') . ')';
                  return Html::a(Html::encode($username), ['edit', 'id' => $model->id], ['data-pjax' => 0]) .
                  (
                      $model->id === Yii::$app->user->id
                      ? ' <span class="label label-info">' . Yii::t('app', 'it`s me') . '</span>'
                      : ''
                  );
              }
          ],
          /**
           * @var email
           */
          [
              'attribute' => 'email',
              'format' => 'raw',
              'value' => function ($model) {
                  $email = $model['email'] ? $model['email'] : '(' . Yii::t('app', 'not set') . ')';
                  return Html::a(Html::encode($email), ['edit', 'id' => $model->id], ['data-pjax' => 0]);
              }
          ],
          /**
           * @var date_create
           */
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
          /**
           * @var IP
           */
          [
              'attribute' => 'ip',
              'format' => 'text',
              'headerOptions' => ['style' => 'width: 150px'],
              'value' => function ($model) {
                  return long2ip($model->ip);
              },
          ],
          /**
           * @var role
           */
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
                  return $model->role ? $model->role->description : '—';
              },
          ],
          /**
           * @var status
           */
          [
              'attribute' => 'status',
              'format' => 'raw',
              'value' => function ($model) {
                  $classes = [
                      $model::STATUS_ACTIVE => 'success',
                      $model::STATUS_BLOCKED => 'warning',
                      $model::STATUS_DELETED => 'danger',
                  ];
                  return
                  '<span class="label label-' . $classes[$model->status ] . '">'
                    . $model->getStatusName() .
                  '</span>';
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
              'buttons' => [
                  'status' => function ($url, $model) {
                      if ($model->isBlocked()) {
                          return Html::a(
                              '<span class="glyphicon glyphicon-play"></span>',
                              ['set-active', 'id' => $model->primaryKey],
                              [
                                  'title' => Yii::t('app', 'Enable'),
                                  'class' => 'submit btn btn-xs btn-success',
                                  'data-pjax' => 0
                              ]
                          );
                      } else {
                          return Html::a(
                              '<span class="glyphicon glyphicon-pause"></span>',
                              ['set-block', 'id' => $model->primaryKey],
                              [
                                  'title' => Yii::t('app', 'Disable'),
                                  'class' => 'submit btn btn-xs btn-warning',
                                  'data-pjax' => 0
                              ]
                          );
                      }
                  },
                  'delete' => function ($url, $model) {
                      return Html::a(
                          '<span class="glyphicon glyphicon-remove"></span>',
                          ['delete', 'id' => $model->primaryKey],
                          [
                              'title' => Yii::t('app', 'Delete'),
                              'class' => 'confirmation submit btn btn-xs btn-danger',
                              'data-pjax' => 0,
                              'data-confirmation' => Yii::t('app', 'Are you sure you want to delete this record?')
                          ]
                      );
                  }
              ]
          ],
      ],
  ]) ?>

  <?php \yii\widgets\Pjax::end(); ?>
<?= Html::endForm(); ?>
