<?php

namespace app\models;

use app\components\BaseActive;
use yii\imagine;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Imagine\Image\ManipulatorInterface;
use Yii;

/**
 * This is the model class for table "file".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $owner_id
 * @property integer $owner_type
 * @property string $title
 * @property string $name
 * @property integer $size
 * @property string $mime
 * @property string $date_create
 * @property string $date_update
 * @property integer $ip
 * @property integer $position
 */
class File extends BaseActive
{
    const UPLOAD_DIR_TMP = 'uploads/files/tmp';
    const UPLOAD_DIR = 'uploads/files';

    //
    // owner types
    //
    const OWNER_TYPE_NEWS_GALLERY = 1;
    const OWNER_TYPE_NEWS_PREVIEW = 2;
    const OWNER_TYPE_NEWS_TEXT    = 3;

    const OWNER_TYPE_USER_PHOTO = 4;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'file';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User'),
            'owner_id' => Yii::t('app', 'Owner'),
            'owner_type' => Yii::t('app', 'Owner type'),
            'title' => Yii::t('app', 'Title'),
            'name' => Yii::t('app', 'Name'),
            'size' => Yii::t('app', 'Size'),
            'mime' => Yii::t('app', 'Mime'),
            'date_create' => Yii::t('app', 'Date create'),
            'date_update' => Yii::t('app', 'Date update'),
            'ip' => Yii::t('app', 'IP'),
            'position' => Yii::t('app', 'Position'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'date_create',
                'updatedAtAttribute' => 'date_update',
                'value' => new \yii\db\Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_DELETE => 'beforeDelete',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->user_id = Yii::$app->user->isGuest ? 0 : Yii::$app->user->id;
                $this->ip = ip2long(Yii::$app->request->getUserIP());
            }

            return true;
        }

        return false;
    }

    /**
     * Generate a name for new file.
     *
     * @param string $extension
     * @return string
     */
    public static function generateName($extension = null)
    {
        $name = date('YmdHis') . substr(md5(microtime() . uniqid()), 0, 10);
        return $extension ? $name . '.' . $extension : $name;
    }

    /**
     * Path to temporary directory of file.
     *
     * @param bool $full
     * @return string
     */
    public function dirTmp($full = false)
    {
        return
            ($full ? Yii::getAlias('@webroot') : '') .
            '/' . self::UPLOAD_DIR_TMP .
            '/' . $this->owner_type;
    }

    /**
     * Path to directory of file.
     *
     * @param bool $full.
     * @return string
     */
    public function dir($full = false)
    {
        if ($this->tmp) {
            return $this->dirTmp($full);
        } else {
            return
                ($full ? Yii::getAlias('@webroot') : '') .
                '/' . self::UPLOAD_DIR .
                '/' . $this->owner_type .
                '/' . $this->owner_id;
        }
    }

    /**
     * Path to file.
     *
     * @param bool $full
     * @return string
     */
    public function pathTmp($full = false)
    {
        return $this->dirTmp($full) . '/'. $this->name;
    }

    /**
     * Path to file.
     *
     * @param bool $full
     * @return string
     */
    public function path($full = false)
    {
        return $this->dir($full) . '/'. $this->name;
    }

    /**
     * Create file from uploader (UploadedFile).
     *
     * @param UploadedFile $data
     * @param int $ownerType
     * @param bool $saveFile Save temporary file.
     * @return File|bool
     */
    public static function createFromUploader($data, $ownerType, $saveFile = false)
    {
        $fileInfo = pathinfo($data->name);

        $file = new self([
            'tmp' => true,
            'owner_type' => $ownerType,
            'size' => $data->size,
            'mime' => $data->type,
            'title' => $fileInfo['filename'],
            'name' => self::generateName($fileInfo['extension'])
        ]);

        if (FileHelper::createDirectory($file->dir(true))) {
            if (move_uploaded_file($data->tempName, $file->path(true))) {
                $file = $saveFile ? self::saveFile($file, $file->owner_type) : $file;
                if ($file->save()) {
                    return $file;
                }
            }
        }

        return false;
    }

    /**
     * Create file from Url
     *
     * @param string $url
     * @param int $ownerType
     * @param bool $saveFile Save temporary file.
     * @return File|bool
     */
    public static function createFromUrl($url, $ownerType, $saveFile = false)
    {
        $tmpFile = tempnam(sys_get_temp_dir(), 'file');

        if ($tmpFileContent = @file_get_contents($url)) {
            if (@file_put_contents($tmpFile, $tmpFileContent)) {
                $fileInfo = pathinfo($url);

                $file = new self([
                    'tmp' => true,
                    'owner_type' => $ownerType,
                    'size' => filesize($tmpFile),
                    'mime' => FileHelper::getMimeType($tmpFile),
                    'title' => $fileInfo['filename'],
                    'name' => self::generateName($fileInfo['extension'])
                ]);

                if (FileHelper::createDirectory($file->dir(true))) {
                    if (rename($tmpFile, $file->path(true))) {
                        $file = $saveFile ? self::saveFile($file, $file->owner_type) : $file;
                        if ($file->save()) {
                            return $file;
                        }
                    }
                }
            }
        }

        return false;
    }

    /**
     * Check owner.
     *
     * @param File $file
     * @param int $ownerId
     * @param int $ownerType
     * @return bool
     */
    public static function checkOwner($file, $ownerId, $ownerType)
    {
        $ownerType = $file->owner_type === $ownerType;
        $ownerId = $file->owner_id === $ownerId;
        $user = $file->user_id === Yii::$app->user->id || $file->user_id === 0;

        return
            (!$file->tmp && $ownerType && $ownerId) ||
            ($file->tmp && $ownerType && $user);
    }

    /**
     * Binding files with owner.
     *
     * @param int $ownerId
     * @param int $ownerType
     * @param array|int $fileId
     * @return File|bool|array
     */
    public static function bind($ownerId, $ownerType, $fileId)
    {
        if ($fileId === [] || $fileId === '') {
            self::deleteByOwner($ownerId, $ownerType);
            return true;
        }

        return is_array($fileId)
            ? self::bindMultiple($ownerId, $ownerType, $fileId)
            : self::bindSingle($ownerId, $ownerType, $fileId);
    }

    /**
     * Binding file with owner.
     *
     * @param int $ownerId
     * @param int $ownerType
     * @param int $fileId
     * @return File|bool
     */
    public static function bindSingle($ownerId, $ownerType, $fileId)
    {
        $file = $fileId ? static::findOne($fileId) : false;

        // check owner
        if (!$file || !self::checkOwner($file, $ownerId, $ownerType)) {
            return false;
        }

        // save tmp file
        if ($file->tmp && $file = self::saveFile($file, $ownerId)) {
            $file->updateAttributes(['tmp' => $file->tmp, 'owner_id' => $file->owner_id]);
        } else {
            return false;
        }

        // delete unnecessary files
        $currentFiles = self::getByOwner($ownerId, $ownerType);
        foreach ($currentFiles as $currFile) {
            if ($currFile->id !== $file->id) {
                $currFile->delete();
            }
        }

        return $file;
    }

    /**
     * Binding files with owner.
     *
     * @param int $ownerId
     * @param int $ownerType
     * @param array $files
     * @return array|bool
     */
    public static function bindMultiple($ownerId, $ownerType, $files)
    {
        // prepare files
        $files = array_filter($files);
        $files = array_combine(array_map(function ($a) {
            return substr($a, 2);
        }, array_keys($files)), $files);

        // get new files
        $newFiles = static::findAll(array_keys($files));
        $newFiles = ArrayHelper::index($newFiles, 'id');

        // get current files
        $currentFiles = self::getByOwner($ownerId, $ownerType);
        $currentFiles = ArrayHelper::index($currentFiles, 'id');

        if (count($newFiles)) {
            foreach ($newFiles as $file) {
                // check owner
                if (!self::checkOwner($file, $ownerId, $ownerType)) {
                    unset($newFiles[$file->id]);
                    continue;
                }
                // save tmp file
                if ($file->tmp) {
                    $file = self::saveFile($file, $ownerId);
                    if (!$file) {
                        return false;
                    }
                }

                $file->updateAttributes([
                    'tmp'      => $file->tmp,
                    'owner_id' => $file->owner_id,
                    'title'    => @$files[$file->id],
                    'position' => @array_search($file->id, array_keys($files)) + 1
                ]);
            }

            // delete unnecessary files
            foreach ($currentFiles as $currFile) {
                if (!array_key_exists($currFile->id, $newFiles)) {
                    $currFile->delete();
                }
            }

        } else {
            // if empty array — delete current files
            foreach ($currentFiles as $currFile) {
                $currFile->delete();
            }
        }

        return $newFiles;
    }

    /**
     * Save file.
     *
     * @param File $file
     * @return File|bool
     */
    public static function saveFile($file, $ownerId)
    {
        $file->tmp = false;
        $file->owner_id = $ownerId;

        if (file_exists($file->pathTmp(true)) && FileHelper::createDirectory($file->dir(true))) {
            if (rename($file->pathTmp(true), $file->path(true))) {
                return $file;
            }
        }

        return false;
    }

    /**
     * Resize.
     *
     * @param string $file
     * @param int $width
     * @param int $height
     * @param bool $ratio
     * @param bool $replace
     * @return string
     */
    public static function resize($file, $width, $height, $ratio = false, $replace = false)
    {
        if (!file_exists(Yii::getAlias('@webroot') . $file)) {
            return $file;
        }

        if ($replace) {
            $thumb = $file;
        } else {
            $fileName = pathinfo($file, PATHINFO_FILENAME);
            $thumb = str_replace($fileName, $width . 'x' . $height . '_' . $fileName, $file);

            if (file_exists(Yii::getAlias('@webroot') . $thumb)) {
                return $thumb;
            }
        }

        $imagine = imagine\Image::getImagine();

        try {
            $image = $imagine->open(Yii::getAlias('@webroot') . $file);
            $image = self::resizeMagic($image, $width, $height, $ratio);

            $image->save(Yii::getAlias('@webroot') . $thumb, [
                'jpeg_quality' => 100,
                'png_compression_level' => 9
            ]);

        } catch (Exception $exception) {
            return $file;
        }

        return $thumb;
    }

    /**
     * Magick resizing method.
     *
     * @param imagine\Image $image
     * @param int $width
     * @param int $height
     * @param bool $ratio
     * @return imagine\Image
     */
    private static function resizeMagic($image, $width, $height, $ratio)
    {
        if ($width < 1 || $height < 1) {
            if ($height < 1) {
                $image = $image->resize($image->getSize()->widen($width));
            } else {
                $image = $image->resize($image->getSize()->heighten($height));
            }

        } else {
            $size = new Box($width, $height);

            if ($ratio) {
                $mode = ImageInterface::THUMBNAIL_INSET;
            } else {
                $mode = ImageInterface::THUMBNAIL_OUTBOUND;
            }

            $image = $image->thumbnail($size, $mode);
        }

        return $image;
    }

    /**
     * Get by owner.
     *
     * @param int $ownerId
     * @param int $ownerType
     * @return array
     */
    public static function getByOwner($ownerId, $ownerType)
    {
        return static::find()
            ->where(['owner_id' => $ownerId, 'owner_type' => $ownerType])
            ->orderBy('position ASC')
            ->all();
    }

    /**
     * Delete by owner.
     *
     * @param int $ownerId
     * @param int $ownerType
     */
    public static function deleteByOwner($ownerId, $ownerType)
    {
        $files = self::getByOwner($ownerId, $ownerType);

        foreach ($files as $file) {
            $dir = $file->dir(true);
            $file->delete();
        }

        if (isset($dir) && !empty($dir)) {
            FileHelper::removeDirectory($dir);
        }
    }

    /**
     * Deleting a file from the db and from the file system.
     *
     * @return bool
     */
    public function beforeDelete()
    {
        if (file_exists($this->path(true))) {
            return @unlink($this->path(true));
        }

        return true;
    }
}
