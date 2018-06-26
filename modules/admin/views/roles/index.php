<?php
use yii\helpers\Html;
use yii\grid\CheckboxColumn;
use yii\grid\GridView;

$this->title = Yii::t('app', 'Roles');
?>
<?= Html::a(Yii::t('app', 'Create'), ['edit'], ['class' => 'btn btn-default']) ?>

<?= Html::beginForm(['batch'], 'post') ?>
  <?php \yii\widgets\Pjax::begin(); ?>

  <?= GridView::widget([
      'dataProvider' => $provider,
      'filterModel'  => $search,
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
             'data-confirmation' => Yii::t('app', 'Are you sure you want to delete this roles?'),
             'data-loading-text' => Yii::t('app', 'Please wait…'),
             'class' => 'submit disabled confirmation btn btn-danger btn-xs'
         ]) . '
       </div>
      {pager}
      ',
      'tableOptions' => ['class' => 'table ' . ($provider->count ? 'table-hover' : '')],
      'columns' => [
         /**
          * @var id
          */
          [
              'class' => CheckboxColumn::class,
              'headerOptions' => ['style' => 'width: 30px']
          ],
          /**
           * @var name
           */
          [
              'attribute' => 'name',
              'format' => 'raw',
              'value' => function ($model) {
                  return Html::a(Html::encode($model->name), ['edit', 'name' => $model->name], ['data-pjax' => 0]);
              }
          ],
          /**
           * @var description
           */
          [
              'attribute' => 'description',
              'enableSorting' => false,
          ],
          // action buttons
          [
              'class' => 'yii\grid\ActionColumn',
              'headerOptions' => ['class' => 'text-right', 'style' => 'width: 40px'],
              'template' => '{delete}',
              'buttons' => [
                  'delete' => function ($url, $model) {
                      return Html::a(
                          '<span class="glyphicon glyphicon-remove"></span>',
                          ['delete', 'id' => $model->primaryKey],
                          [
                              'title' => Yii::t('app', 'Delete'),
                              'class' => 'confirmation submit btn btn-xs btn-danger',
                              'data-pjax' => 0,
                              'data-confirmation' => Yii::t('app', 'Are you sure you want to delete this role?')
                          ]
                      );
                  }
              ]
          ],
      ],
  ]) ?>

  <?php \yii\widgets\Pjax::end(); ?>
<?= Html::endForm(); ?>
