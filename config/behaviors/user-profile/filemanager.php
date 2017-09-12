<?php

use Intervention\Image\ImageManagerStatic as Image;
use yii\helpers\ArrayHelper;
use app\models\entity\File;
use app\helpers\Random;

return [
    'class' => 'rkit\filemanager\behaviors\FileBehavior',
    'attributes' => [
        'photo' => [
            'storage' => 'localFs',
            'baseUrl' => '@web/uploads',
            'type' => 'image',
            'relation' => 'photoFile',
            'saveFilePathInAttribute' => true,
            'templatePath' => function ($file) {
                $date = new \DateTime(is_object($file->date_create) ? null : $file->date_create);
                return '/' . $date->format('Ym') . '/' . $file->id . '/' . $file->name;
            },
            'createFile' => function ($path, $name) {
                $file = new File();
                $file->title = $name;
                $file->name = Random::fileName(pathinfo($name, PATHINFO_EXTENSION));
                $file->save();
                return $file;
            },
            'rules' => [
                'imageSize' => ['minWidth' => 100, 'minHeight' => 100],
                'mimeTypes' => ['image/png', 'image/jpg', 'image/jpeg'],
                'extensions' => ['jpg', 'jpeg', 'png'],
                'maxFiles' => 1,
                'maxSize' => 1024 * 1024 * 1, // 1 MB
            ],
            'preset' => [
                '1000x1000' => function ($realPath, $publicPath) {
                    Image::make($realPath . $publicPath)
                        ->resize(1000, 1000, function ($constraint) {
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        })
                        ->save(null, 100);
                },
            ],
            'applyPresetAfterUpload' => ['1000x1000']
        ],
    ]
];
