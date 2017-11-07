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
             'data-confirmation' => Yii::t('app', 'Are you sure you want to delete this users?'),
             'data-loading-text' => Yii::t('app', 'Please wait…'),
             'class' => 'submit disabled confirmation btn btn-danger btn-xs'
         ]) . '
         ' . Html::submitButton(Yii::t('app', 'active'), [
             'name' => 'operation',
             'value' => 'set-active',
             'data-confirmation' => Yii::t('app', 'Are you sure you want to make active this users?'),
             'data-loading-text' => Yii::t('app', 'Please wait…'),
             'class' => 'submit disabled confirmation btn btn-success btn-xs'
         ]) . '
         ' . Html::submitButton(Yii::t('app', 'block'), [
             'name' => 'operation',
             'value' => 'set-block',
             'data-confirmation' => Yii::t('app', 'Are you sure you want to block this users?'),
             'data-loading-text' => Yii::t('app', 'Please wait…'),
             'class' => 'submit disabled confirmation btn btn-warning btn-xs'
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
           * @var id
           */
          [
              'attribute' => 'id',
              'format' => 'raw',
              'headerOptions' => ['style' => 'width: 75px'],
              'value' => function ($model) {
                  return Html::a(Html::encode($model->id), ['edit', 'id' => $model->id], ['data-pjax' => 0]);
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
                  return Html::a(Html::encode($email), ['edit', 'id' => $model->id], ['data-pjax' => 0]) .
                  (
                      $model->id === Yii::$app->user->id
                      ? ' <span class="label label-info">' . Yii::t('app', 'it`s me') . '</span>'
                      : ''
                  );
              }
          ],
          /**
           * @var date_create
           */
          [
              'attribute' => 'date_create',
              'format' => 'datetime',
              'headerOptions' => ['style' => 'width: 180px'],
              'filter' => DatePicker::widget(
                  [
                      'model' => $userSearch,
                      'attribute' => 'date_create',
                      'type' => DatePicker::TYPE_RANGE,
                      'attribute' => 'date_create_start',
                      'attribute2' => 'date_create_end',
                      'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd',
                      ],
                      'options' => [
                          'class' => 'form-control',
                      ],
                  ]
              ),
          ],
          /**
           * @var date_login
           */
          [
              'attribute' => 'date_login',
              'format' => 'datetime',
              'headerOptions' => ['style' => 'width: 180px'],
              'filter' => DatePicker::widget(
                  [
                      'model' => $userSearch,
                      'type' => DatePicker::TYPE_RANGE,
                      'attribute' => 'date_login_start',
                      'attribute2' => 'date_login_end',
                      'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd',
                      ],
                      'options' => [
                          'class' => 'form-control',
                      ],
                  ]
              ),
          ],
          /**
           * @var role
           */
          [
              'attribute' => 'role_name',
              'format' => 'raw',
              'filter' => Html::activeDropDownList(
                  $userSearch,
                  'role_name',
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
          /**
           * @var view
           */
        //   [
        //       'format' => 'raw',
        //       'headerOptions' => ['style' => 'width: 35px'],
        //       'value' => function ($model) {
        //           return Html::a(
        //               Html::tag('i', '', ['class' => 'glyphicon glyphicon-eye-open']),
        //               ['/users/view', 'id' => $model->id], ['data-pjax' => 0, 'target' => '_blank']
        //           );
        //       }
        //   ],
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
                                  'title' => Yii::t('app', 'Activate'),
                                  'class' => 'confirmation submit btn btn-xs btn-success',
                                  'data-pjax' => 0,
                                  'data-confirmation' => Yii::t('app', 'Are you sure you want to make active this user?')
                              ]
                          );
                      } else {
                          return Html::a(
                              '<span class="glyphicon glyphicon-pause"></span>',
                              ['set-block', 'id' => $model->primaryKey],
                              [
                                  'title' => Yii::t('app', 'Block'),
                                  'class' => 'confirmation submit btn btn-xs btn-warning',
                                  'data-pjax' => 0,
                                  'data-confirmation' => Yii::t('app', 'Are you sure you want to block this user?')
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
                              'data-confirmation' => Yii::t('app', 'Are you sure you want to delete this user?')
                          ]
                      );
                  }
              ]
          ],
      ],
  ]) ?>

  <?php \yii\widgets\Pjax::end(); ?>
<?= Html::endForm(); ?>
