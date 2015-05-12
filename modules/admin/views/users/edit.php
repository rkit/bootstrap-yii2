<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\User;
use app\widgets\FileApi\Widget as FileApi;

$this->title = Yii::t('app', 'Users');
?>
<?= $this->render('/shared/forms/header', ['title' => $model->username ?: $model->email, 'model' => $model]) ?>

<?php if (!$model->isNewRecord): ?>
<ul class="nav nav-tabs">
    <li class="active"><?= Html::a(Yii::t('app', 'Main information'), ['edit', 'id' => $model->id]) ?></li>
    <li><?= Html::a(Yii::t('app', 'Profile'), ['profile', 'id' => $model->id]) ?></li>
</ul><br>
<?php endif ?>

<?php $form = ActiveForm::begin(['options' => ['class' => 'form']]); ?>

    <div class="row">
        <div class="col-md-<?= $model->isNewRecord ? '12' : '8' ?>">

            <!-- role -->
            <?= $form->field($model, 'role')
                ->dropDownList(ArrayHelper::map($roles, 'name', 'description'), [
                    'class' => 'form-control',
                    'prompt' => Yii::t('app', 'No role')
                ])
                ->label(Html::a(Yii::t('app', 'Role'), Url::toRoute('/admin/roles'))); ?>

            <!-- username -->
            <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

            <!-- email -->
            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

            <!-- passwordNew -->
            <input type="password" name="password" id="password_" style="display: none">
            <?= $form->field($model, 'passwordNew')->passwordInput(['maxlength' => true]) ?>

            <!-- status -->
            <?= $form->field($model, 'status')
                ->dropDownList($model->getStatuses(), [
                    'class' => 'form-control',
                    'prompt' => Yii::t('app', 'Select status')
                ]
            ); ?>

        </div>
        <?php if (!$model->isNewRecord) : ?>
        <div class="col-md-4">
            <ul class="list-group">
                <li class="list-group-item text-muted"><?= Yii::t('app', 'Info') ?></li>
                <li class="list-group-item text-right">
                    <span class="pull-left"><strong><?= Yii::t('app', 'Joined') ?></strong></span>
                    <?= Yii::$app->formatter->asDateTime($model->date_create) ?>
                </li>
                <li class="list-group-item text-right">
                    <span class="pull-left"><strong><?= Yii::t('app', 'Last login') ?></strong></span>
                    <?= $model->date_login > 0 ? Yii::$app->formatter->asDateTime($model->date_login) : '—' ?>
                </li>
                <li class="list-group-item text-right">
                    <span class="pull-left"><strong><?= Yii::t('app', 'IP') ?></strong></span>
                    <?= long2ip($model->ip) ?>
                </li>
            </ul>
        </div>
        <?php if (count($model->providers())) : ?>
        <div class="col-md-4">
            <ul class="list-group">
                <li class="list-group-item text-muted"><?= Yii::t('app', 'Social Networks') ?></li>
                <?php foreach ($model->providers() as $provider) : ?>
                <li class="list-group-item text-right">
                    <span class="pull-left">
                        <strong><?= ucfirst(User::getProviders()[$provider['provider']]) ?></strong>
                    </span>
                    <?= Html::a(Yii::t('app', 'Link to profile'), $provider['profile_url'], ['target' => '_blank']) ?>
                </li>
                <?php endforeach?>
            </ul>
        </div>
        <?php endif?>
        <?php if (!$model->isConfirmed()): ?>
        <div class="col-md-4">
            <div class="alert alert-warning" role="alert">
                <?= Yii::t('app', 'Account not activated') ?>
            </div>
        </div>
        <?php endif?>
        <?php endif?>
    </div>

    <?= $this->render('/shared/forms/controls', ['model' => $model]) ?>

<?php ActiveForm::end(); ?>
