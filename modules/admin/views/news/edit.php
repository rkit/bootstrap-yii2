<?php
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\widgets\DateTimePicker;

$this->title = Yii::t('app', 'News');
?>
<?= $this->render('/shared/forms/header', ['title' => $model->title, 'model' => $model]) ?>

<?php $form = ActiveForm::begin(['options' => ['class' => 'form']]); ?>

    <!-- title -->
    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <!-- type_id -->
    <?= $form->field($model, 'type_id')
        ->dropDownList(ArrayHelper::map($types, 'id', 'title'), [
            'class' => 'form-control',
            'prompt' => Yii::t('app', 'Select the type')
        ])
        ->label(Html::a(Yii::t('app', 'Type'), Url::toRoute('/admin/news-types'))); ?>

    <!-- text -->
    <?= $this->render('/shared/editor', [
        'form' => $form, 'model' => $model, 'attribute' => 'text', 'imageUploadUrl' => 'text-upload'
    ]) ?>

    <!-- preview -->
    <?= $this->render('/shared/files/image-input', [
        'form' => $form,
        'model' => $model,
        'attribute' => 'preview',
        'crop' => true,
        'cropMinSize' => [300, 300]
    ]) ?>

    <!-- gallery -->
    <hr>
    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingOne">
                <h4 class="panel-title">
                    <span class="glyphicon glyphicon-picture"></span>
                    <a data-toggle="collapse" data-parent="#accordion"
                       href="#accordion-<?= Html::getInputId($model, 'gallery') ?>" aria-expanded="true">
                        <?= $model->getAttributeLabel('gallery') ?>
                    </a>
                </h4>
            </div>
            <div id="accordion-<?= Html::getInputId($model, 'gallery') ?>" class="panel-collapse collapse"
                 role="tabpanel" aria-labelledby="headingOne">
                <div class="panel-body">
                    <?= $this->render('/shared/files/gallery-input', [
                        'form' => $form, 'model' => $model, 'attribute' => 'gallery'
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
    <hr>

    <!-- date_pub -->
    <?php $model->date_pub = Yii::$app->formatter->asDatetime($model->date_pub ?: 'now', 'php:Y-m-d H:i:s'); ?>
    <?= $form->field($model, 'date_pub')->widget(DateTimePicker::className(), [
    	'pluginOptions' => [
    		'autoclose' => true,
    		'format' => 'yyyy-mm-dd hh:ii:ss'
    	]
    ]) ?>

    <!-- tags -->
    <?= $this->render('/shared/tags', ['form' => $form, 'model' => $model, 'attribute' => 'tagsList']) ?>

    <!-- reference -->
    <?= $form->field($model, 'reference')->textInput(['maxlength' => true]) ?>

    <!-- status -->
    <?= $form->field($model, 'status')->checkbox(['label' => Yii::t('app', 'Publish')]) ?>

    <?= $this->render('/shared/forms/controls', ['model' => $model]) ?>

<?php ActiveForm::end(); ?>
