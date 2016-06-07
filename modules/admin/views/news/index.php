<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\grid\CheckboxColumn;
use yii\grid\GridView;
use kartik\date\DatePicker;

$this->title = Yii::t('app', 'News');
?>
<?= Html::a(Yii::t('app', 'Add'), Url::toRoute('edit'), ['class' => 'btn btn-default']) ?>

<?= Html::beginForm(Url::toRoute('operations'), 'post') ?>
  <?php \yii\widgets\Pjax::begin(); ?>

  <?= GridView::widget([
      'dataProvider' => $dataProvider,
      'filterModel'  => $newsSearch,
      'options' => ['class' => 'gridview'],
      'layout' =>
      '<div class="panel panel-default">
         <div class="panel-body panel-table">
           <div class="table-responsive">{items}</div>
         </div>
         <div class="panel-footer">{summary}</div>
       </div>
       <div class="operations">
         ' . Html::submitButton(Yii::t('app', 'delete'), [
             'name' => 'operation',
             'value' => 'delete',
             'data-confirmation' => Yii::t('app', 'Are you sure you want to delete this records?'),
             'data-loading-text' => Yii::t('app', 'Please wait…'),
             'class' => 'submit disabled confirmation btn btn-danger btn-xs'
         ]) . '
         ' . Html::submitButton(Yii::t('app', 'publish'), [
             'name' => 'operation',
             'value' => 'set-publish',
             'data-loading-text' => Yii::t('app', 'Please wait…'),
             'class' => 'submit disabled btn btn-success btn-xs'
         ]) . '
         ' . Html::submitButton(Yii::t('app', 'unpublish'), [
             'name' => 'operation',
             'value' => 'set-unpublish',
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
              'class' => CheckboxColumn::classname(),
              'headerOptions' => ['style' => 'width: 30px']
          ],
          /**
           * @var title
           */
          [
              'attribute' => 'title',
              'format' => 'raw',
              'headerOptions' => ['style' => 'width: 400px'],
              'value' => function ($model) {
                  return Html::a(Html::encode($model['title']), ['edit', 'id' => $model['id']], ['data-pjax' => 0]);
              }
          ],
          /**
           * @var type_id
           */
          [
              'attribute' => 'type_id',
              'value' => 'type.title',
              'filter' => Html::activeDropDownList(
                  $newsSearch,
                  'type_id',
                  ArrayHelper::map($types, 'id', 'title'),
                  ['class' => 'form-control', 'prompt' => Yii::t('app', 'All types')]
              )

          ],
          /**
           * @var date_pub
           */
          [
              'attribute' => 'date_pub',
              'format' => 'datetime',
              'headerOptions' => ['style' => 'width: 200px'],
              'filter' => DatePicker::widget(
                  [
                      'model' => $newsSearch,
                      'attribute' => 'date_pub',
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
          /**
           * @var status
           */
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
              'headerOptions' => ['class' => 'text-right', 'style' => 'width: 70px'],
              'template' => '{status} {delete}',
              'buttons' => [
                  'status' => function ($url, $model) {
                      if ($model->status == $model::STATUS_BLOCKED) {
                          return Html::a(
                              '<span class="glyphicon glyphicon-play"></span>',
                              ['set-publish', 'id' => $model->primaryKey],
                              [
                                  'title' => Yii::t('app', 'Enable'),
                                  'class' => 'submit btn btn-xs btn-success',
                                  'data-pjax' => 0
                              ]
                          );
                      } else {
                          return Html::a(
                              '<span class="glyphicon glyphicon-pause"></span>',
                              ['set-unpublish', 'id' => $model->primaryKey],
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
