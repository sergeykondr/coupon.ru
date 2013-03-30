<?php
/**
 *
 * !Attributes - атрибуты БД
 *
 * @property string $id
 * @property string $object_id
 * @property string $model_id
 * @property string $name
 * @property string $tag
 * @property string $title
 * @property string $descr
 * @property string $order
 * @property string $path
 *
 * !Accessors - Геттеры и сеттеры класа и его поведений
 * @property        $deleteUrl
 * @property        $isImage
 * @property        $isAudio
 * @property        $isExcel
 * @property        $isWord
 * @property        $isFileExist
 * @property        $isArchive
 * @property        $icon
 * @property        $handler
 * @property string $size            formatted file size
 * @property        $extension
 * @property        $nameWithoutExt
 * @property        $content
 * @property        $downloadUrl
 * @property        $hash
 * @property        $href
 * @property        $serverDir
 * @property        $serverPath
 * @property        $errorsFlatArray
 * @property string $error           the error message. Null is returned if no error.
 *
 */

class MediaFile extends ActiveRecord
{
    const UPLOAD_PATH  = 'upload/mediaFiles';
    const FILE_POSTFIX = '';


    const TYPE_IMG   = 'img';
    const TYPE_VIDEO = 'video';
    const TYPE_AUDIO = 'audio';
    const TYPE_DOC   = 'doc';


    public $types = array(
        self::TYPE_IMG   => self::TYPE_IMG,
        self::TYPE_VIDEO => self::TYPE_VIDEO,
        self::TYPE_AUDIO => self::TYPE_AUDIO,
        self::TYPE_DOC   => self::TYPE_DOC,
    );

    public $error;


    public function name()
    {
        return 'Файловый менеджер';
    }


    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }


    public function tableName()
    {
        return 'media_files';
    }


    public function primaryKey()
    {
        return 'id';
    }


    public function rules()
    {
        return array(
            array(
                'path',
                'length',
                'min'=> 1
            ),
            array(
                'nameWithoutExt',
                'length',
                'min'      => 1,
                'max'      => 900,
                'tooShort' => 'Название файла должно быть меньше 1 сим.',
                'tooLong'  => 'Пожалуйста, сократите наименование файла до 900 сим.'
            )
        );
    }


    public function parent($model_id, $id)
    {
        $alias = $this->getTableAlias();
        $this->getDbCriteria()->mergeWith(array(
            'condition' => "$alias.model_id='$model_id' AND $alias.object_id='$id'",
            'order'     => "$alias.order DESC"
        ));
        return $this;
    }


    public function tag($tag)
    {
        $alias = $this->getTableAlias();
        $this->getDbCriteria()->mergeWith(array(
            'condition' => "$alias.tag='$tag'"
        ));
        return $this;
    }


    public function getDeleteUrl()
    {
        return Yii::app()->createUrl('/media/mediaFileAdmin/delete', array('id' => $this->id));
    }


    protected function isType($type)
    {
        return in_array($this->extension, MediaFileExtensions::${$type . 'Extensions'});
    }


    public function getIsImage()
    {
        return $this->isType('image');
    }


    public function getIsAudio()
    {
        return $this->isType('audio');
    }


    public function getIsExcel()
    {
        return $this->isType('excel');
    }


    public function getIsWord()
    {
        return $this->isType('word');
    }

    public function getIsVideo()
    {
        return $this->isType('video');
    }


    public function getIsArchive()
    {
        return $this->isType('archive');
    }


    public function getIsDocument()
    {
        return $this->isType('readable') || $this->isArchive || $this->isWord || $this->isExcel;
    }


    public function getIsFileExist()
    {
        $filename = Yii::app()->getBasePath() . '/../' . $this->path . '/' . $this->name;
        return file_exists($filename) && is_file($filename);
    }


    public function getIcon()
    {
        $folder = Yii::app()->getModule('media')->assetsUrl() . '/img/fileIcons/';
        switch (true)
        {
            case $this->isImage:
                $img = ImageHelper::thumb($this->getServerDir(), $this->name, array(
                    'width'  => 48,
                    'height' => 48
                ), true);
                return $img->__toString();
                break;
            case $this->isAudio:
                $name = 'audio';
                break;
            case $this->isExcel:
                $name = 'excel';
                break;
            case $this->isWord:
                $name = 'word';
                break;
            case $this->isArchive:
                $name = 'rar';
                break;
            default:
                $name = is_file('.' . $folder . $this->extension . '.jpg') ? $this->extension : 'any';
                break;
        }

        return CHtml::image($folder . $name . '.jpg', '', array('height' => 48));
    }


    public function getHandler($field = false)
    {
        Yii::import('upload.extensions.upload.Upload');
        $param = $field ? $_FILES[$field] : self::UPLOAD_PATH . $this->name;
        return new Upload($param);
    }


    public function save($runValidation = true, $attributes = null)
    {
        if (!parent::save($runValidation = true, $attributes = null))
        {
            $this->error = Yii::t('MediaModule.main', 'Не удалось сохранить изменения');
            return false;
        }
        return true;
    }


    public function setExtraProperties($field, &$handler, $options)
    {
        $info = getimagesize($_FILES[$field]['tmp_name']);

        if (isset($options['save_y']) && $options['save_y'])
        {
            $size             = isset($options['min_y']) ? $options['min_y'] : 0;
            $handler->image_y = ($info[1] > $size) ? $info[1] : $size;
        }

        if (isset($options['save_x']) && $options['save_x'])
        {
            $size             = isset($options['min_x']) ? $options['min_x'] : 0;
            $handler->image_x = ($info[0] > $size) ? $info[0] : $size;
        }
    }


    public function saveFile()
    {
        $file      = CUploadedFile::getInstanceByName('file');
        $file_name = FileSystemHelper::vaultResolveCollision(self::UPLOAD_PATH, $file->name);
        $new_file  = self::UPLOAD_PATH . '/' . $file_name;
        if ($file->saveAs('./' . $new_file))
        {
            list($this->path, $this->name) = FileSystemHelper::moveToVault($new_file, self::UPLOAD_PATH,
                true);
            $this->title = $file->name;
            return true;
        }
        else
        {
            $this->error = $file->getError();
            return false;
        }
    }


    /**
     * @return string formatted file size
     */
    public function getSize()
    {
        $file = $this->getServerPath();

        $size = is_file($file) ? filesize($file) : NULL;

        $metrics[0] = 'байт';
        $metrics[1] = 'кб.';
        $metrics[2] = 'мб.';
        $metrics[3] = 'гб.';
        $metric     = 0;

        while (floor($size / 1024) > 0)
        {
            ++$metric;
            $size /= 1024;
        }

        $ret = round($size, 1) . " " . (isset($metrics[$metric]) ? $metrics[$metric] : '??');
        return $ret;
    }


    public function getExtension()
    {
        return pathinfo($this->name, PATHINFO_EXTENSION);
    }


    public function getNameWithoutExt()
    {
        $name   = pathinfo($this->name, PATHINFO_FILENAME);
        $params = array(' ' => '');
        if (self::FILE_POSTFIX)
        {
            $params[self::FILE_POSTFIX] = '';
        }
        return strtr($name, $params);
    }


    public function detectType()
    {
        switch (true)
        {
            case $this->isDocument:
                return self::TYPE_DOC;
            case $this->isAudio:
                return self::TYPE_AUDIO;
            case $this->isVideo:
                return self::TYPE_VIDEO;
            case $this->isImage:
                return self::TYPE_IMG;
        }
    }


    public function beforeSave()
    {
        if (parent::beforeSave())
        {
            if ($this->isNewRecord)
            {
                $model       = MediaFile::model()->parent($this->model_id, $this->object_id)->find();
                $this->order = $model ? $model->order + 1 : 1;
                $this->type  = $this->detectType();
            }

            return true;
        }
        return false;
    }


    public function beforeDelete()
    {
        if (parent::beforeDelete())
        {
            if (is_file($this->path .'/'. $this->name))
            {
                //FileSystemHelper::deleteFileWithSimilarNames(self::UPLOAD_PATH . '/crop', $this->name);
                //FileSystemHelper::deleteFileWithSimilarNames(self::UPLOAD_PATH . '/watermark', $this->name);
                @unlink('./' . $this->path .'/'. $this->name);
            }

            if (is_file($this->path .'/310x205_crop_'. $this->name))
            {
                //FileSystemHelper::deleteFileWithSimilarNames(self::UPLOAD_PATH . '/crop', $this->name);
                //FileSystemHelper::deleteFileWithSimilarNames(self::UPLOAD_PATH . '/watermark', $this->name);
                @unlink('./' . $this->path .'/310x205_crop_'. $this->name);
            }

            return true;
        }

        return false;
    }


    public function search($crit = null)
    {
        $criteria = new CDbCriteria;
        $criteria->compare('object_id', $this->object_id, true);
        $criteria->compare('model_id', $this->model_id, true);
        $criteria->compare('tag', $this->tag, true);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('descr', $this->descr, true);
        $criteria->compare('order', $this->order);

        if ($crit)
        {
            $criteria->mergeWith($crit);
        }

        return new ActiveDataProvider(get_class($this), array(
            'criteria' => $criteria
        ));
    }


    public function getContent()
    {
        if (file_exists($this->path))
        {
            return file_get_contents($this->path . '/' . $this->name);
        }
    }


    public function getDownloadUrl()
    {
        $hash = $this->getHash();
        return Yii::app()->getController()->createUrl('/media/mediaFile/downloadFile', array(
            'hash' => "{$hash}x{$this->id}",
        ));
    }


    public function getHash()
    {
        return md5($this->object_id . $this->model_id . $this->name . $this->tag);
    }


    public function getHref()
    {
        if ($this->path && $this->name)
        {
            return '/' . $this->path . '/' . $this->name;
        }
        else
        {
            return Discount::NO_IMAGE;
        }

    }


    public function getServerDir()
    {
        return $_SERVER['DOCUMENT_ROOT'] . $this->path . '/';
    }


    public function getServerPath()
    {
        return $_SERVER['DOCUMENT_ROOT'] . $this->path . '/' . $this->name;
    }
}
