<?php
use yii\helpers\Html;
?>
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

<?php if (count($model->model()->providers)) : ?>
<div class="col-md-4">
	<ul class="list-group">
		<li class="list-group-item text-muted"><?= Yii::t('app', 'Social Networks') ?></li>
		<?php foreach ($model->model()->providers as $provider) : ?>
		<li class="list-group-item text-right">
			<span class="pull-left">
				<strong><?= ucfirst($provider->getTypeName()) ?></strong>
			</span>
			<?= Html::a(Yii::t('app', 'Link to profile'), $provider->profile_url, ['target' => '_blank']) ?>
		</li>
		<?php endforeach?>
	</ul>
</div>
<?php endif?>

<?php if (!$model->model()->isConfirmed()): ?>
<div class="col-md-4">
	<div class="alert alert-warning" role="alert">
		<?= Yii::t('app', 'Account not activated') ?>
	</div>
</div>
<?php endif?>
