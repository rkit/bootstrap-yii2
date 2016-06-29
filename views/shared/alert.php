<?php
use yii\helpers\Html;
$email = Html::mailto(Yii::t('app', 'contact us'), Yii::$app->settings->emailMain);
?>
<?= $message; ?>
<?php if($type == 'error'):?>
<p class="text-muted small"><?= Yii::t('app.messages', 'If you need help, please {email}', ['email' => $email]) ?></p>
<?php endif?>
