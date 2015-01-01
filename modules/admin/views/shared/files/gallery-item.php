<?php
use app\models\File;
use yii\helpers\Html;
?>
<li>
    <a href="<?= $file->path()?>" target="_blank"><img src="<?= File::resize($file->path(), 80, 80)?>"></a>
    <a class="btn btn-lg"><span class="glyphicon glyphicon-remove remove" data-remove-closest="li"></span></a>

    <?= Html::textInput(Html::getInputName($model, $attribute) . '[id' . $file->id .']', $file->title, [
        'class' => 'form-control',
    ])?>
</li>