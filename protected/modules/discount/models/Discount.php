<?
/**
 * @property $id
 * @property $all_buy
 * @property $category_id
 * @property $numbers_buy
 * @property $price
 * @property $text
 * @property $actuality
 */
class Discount extends ActiveRecord
{
    public $all_buy; //всего: накрутка + кол-во купивших реально
    public $cheat_now; //сколько надо накрутить
    public $metrosarray; //для записи массива метро
    public $actuality; //актуальна ли акция. кеш. вззаимодействие с методом isActual
    const PATH_XML_IMG = 'upload/mediaFiles/xml';
    const PATH_XML_IMG_CROP = 'upload/mediaFiles/xml_crop';
    const PATH_OUR_IMG_CROP = 'upload/mediaFiles/our_crop'; //для кропа наших дискаунтов
    const IMG_CROP_WIDTH = 310;
    const IMG_CROP_HEIGHT = 205;


    public function name()
    {
        return 'Discount';
    }


    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }


    public function tableName()
    {
        return 'discount';
    }


    public function behaviors()
    {
        return CMap::mergeArray(
            parent::behaviors(),
            array(
                'MetaTag' => array('class' => 'application.components.activeRecordBehaviors.MetaTagBehavior'),
                'FileManager' => array(
                    'class' => 'application.components.activeRecordBehaviors.FileManagerBehavior',
                    'tags' => array(
                        'gallery' => array(
                            'title' => 'Галерея',
                            'data_type' => 'image'
                        ),
                        'xml' => array(
                            'title' => 'Галерея',
                            'data_type' => 'image'
                        )
                    )
                ),
            )
        );
    }


    public function rules()
    {
        return array(
            array(
                'short_desc,
                company_coordinates, company_time,
                text, cheat, kuponator_exp',
                'required',  'on'=>'our_discount'
            ),

            array(
                'category_id, name, description,
                beginsell, endsell, beginvalid, endvalid,
                company_name, company_url, company_tel, company_address',
                'required',  'on'=>'our_discount, xml_discount'
            ),

            /*
            array(
                'category_id, name, short_desc, description,
                beginsell, endsell, beginvalid, endvalid,
                company_name, company_url, company_tel, company_address, company_coordinates, company_time,
                text, cheat, kuponator_exp',
                'required',  'on'=>'our_discount'
            ),
            */

            array(
                'name',
                'length',
                'max' => 130,
                'on'=>'our_discount,  xml_discount',
             ),

            array(
                'category_id, numbers_buy, price',
                'numerical',
                'integerOnly' => true,
                'on'=>'our_discount'
            ),

            array(
                'metros', 'metrosvalid', //в DiscountForm это поле есть, но мы тамже его принудительно переименовали.
                'on'=>'our_discount',
            ),
            array(
                //нужно чтоб записывалось сюда при ред. нашего дискаута и xml дискаунта. Можно ли тут делать атрибут safe
                'metrosarray', 'safe', // для него нет label ошибки (потому что в конструкторе форм по нормальному это поле не было объявлено)
                //'on'=>'our_discount',
            ),
        );
    }


    public function metrosvalid($attributes,$params)
    {
        if ($this->metrosarray == '')
        $this->addError('metros', 'Заполните метро'); //пользуемся label у metros для вывода ошибки
    }


    //определяем акция актуальна или завершена?
    public function isActual()
    {
        if (isset ($this->actuality))
            return $this->actuality;

        if(strtotime($this->beginsell) <= time() and strtotime($this->endsell) >= time())
        {
            //актуальна
            $this->actuality = true;
            return true;
        }
        else
        {
            //не актуальна
            $this->actuality = false;
            return false;
        }
    }


    public function relations()
    {
        return array(
            'category' => array(self::BELONGS_TO, 'Category', 'category_id'),
            'metrosRell' => array(self::HAS_MANY, 'DiscountMetro','discount_id'),
            'metros'     => array(self::HAS_MANY, 'Metro',array('metro_id'=>'id'),'through' => 'metrosRell'),
        );
    }


    /*накрутка покупок*/
    public function cheat()
    {
        //узнаем обозначенные рамки акции в часах
        $date1 = strtotime($this->getAttribute('beginsell'));
        $date2 = strtotime($this->getAttribute('endsell'));
        $hours =  ($date2 - $date1)/60/60;

        //узнаем сколько голосов надо накручивать в час
        $golosPerHour = $this->getAttribute('cheat')/$hours;

        /*сколько надо накрутить.
        узнаем сколько часов прошло с запуска акции и (*) на golosPerHour
        */
       return floor(((time()-strtotime($this->getAttribute('beginsell')))/60/60) * $golosPerHour);

    }


    /*узнаем сколько времени осталось до завершения*/
    public function expires($inp='short')
    {
        //кол-во часов. по уолчанию выводим в коротком варианте

        $date1 = new DateTime($this->getAttribute('endsell'));
        $date2 = new DateTime('now');
        $interval = date_diff($date1, $date2); // PHP>=5.3 $interval = $date1->date_diff($date2);
        $str=array();
        if ($interval->format('%m'))
            $str[] = $interval->format('%m'). ' мес. ';
        if ($interval->format('%d'))
            $str[]= $interval->format('%d'). ' дн. ';
        if ($interval->format('%h'))
            $str[]= $interval->format('%h'). ' час.';
        if ($interval->format('%i'))
        $str[]= $interval->format('%i').' мин.';
        if ($inp=='long')
            $ret = implode("", $str);
        if ($inp=='short')
        $ret = array_shift($str);

        return $ret;
    }


    public function beforeFind()
    {
        //выбираем только актуальные акции и упорядочиваем их по убыванию кол-ва купивших с учетом накрутки
        /*
        $criteria=new CDbCriteria;
        $criteria->select='*, floor((cheat / ((UNIX_TIMESTAMP( endsell ) - UNIX_TIMESTAMP( beginsell ))/60/60)) * ((UNIX_TIMESTAMP( NOW() ) - UNIX_TIMESTAMP( beginsell ))/60/60)) as cheat_now, (floor((cheat / ((UNIX_TIMESTAMP( endsell ) - UNIX_TIMESTAMP( beginsell ))/60/60)) * ((UNIX_TIMESTAMP( NOW() ) - UNIX_TIMESTAMP( beginsell ))/60/60)) + numbers_buy) as all_buy';
        $criteria->condition='DATEDIFF( endsell, beginsell ) >1';
        $this->getDbCriteria()->mergeWith($criteria);
        parent::beforeFind();
        */
    }


    /*
     * Список дискаунтов для страницы админки "УПРАВЛЕНИЕ ДИСКАУНТАМИ"
     * Возвращает свои (по умолчанию) или импортирванные xml
     *
     */
    public function search($type='our')
    {
        $criteria = new CDbCriteria;
        $criteria->condition = ($type == 'our') ? 'our = 1' : 'our = 0';
        $criteria->compare('id', $this->id, true);
        $criteria->compare('category_id', $this->category_id, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('numbers_buy', $this->numbers_buy, true);
        $criteria->compare('price', $this->price, true);
        $criteria->compare('text', $this->text, true);

        return new ActiveDataProvider(get_class($this), array(
            'criteria'   => $criteria,
            'pagination' =>array(
                'pageSize' => 30
            )
        ));
    }


    public function similarSearch()
    {
        $criteria = new CDbCriteria;
        $criteria->select = '*, floor((cheat / ((UNIX_TIMESTAMP( endsell ) - UNIX_TIMESTAMP( beginsell ))/60/60)) * ((UNIX_TIMESTAMP( NOW() ) - UNIX_TIMESTAMP( beginsell ))/60/60)) as cheat_now, (floor((cheat / ((UNIX_TIMESTAMP( endsell ) - UNIX_TIMESTAMP( beginsell ))/60/60)) * ((UNIX_TIMESTAMP( NOW() ) - UNIX_TIMESTAMP( beginsell ))/60/60)) + numbers_buy) as all_buy';
        $criteria->order = 'all_buy DESC';
        $criteria->limit = 5;
        $criteria->condition = 'DATEDIFF( endsell, beginsell ) >1 AND category_id = ' . $this->category_id . ' AND id <>' . $this->id . ' AND ' . Category::model()->queryActual();

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'pagination' => false,
        ));
    }


    public function getHref()
    {
        return Yii::app()->createUrl('/discount/discount/view', array('id' => $this->id));
    }

    /*
    public function getDatebs(){
        $date = $this->getAttribute('beginsell');
        $date = $date.'asdf';
        return $date;
    }
    */

    public function uploadFiles()
    {
        return array(
        );
    }

    public function inCategory($categoryId)
    {
        $alias = $this->getTableAlias();
        $this->getDbCriteria()->mergeWith(array(
            'condition' => $alias.'.category_id='.$categoryId
        ));
        return $this;
    }


    public function afterSave()
    {
        parent::afterSave();
        //если акция наша. !акции не нашей тоже можно присвоить метро!!!
        if ($this->scenario=='our_discount')
        {
            $this->reSpecifyMetro();
        }
        else
        {
            //если метро указаны, то присваиваем
            if ($this->metrosarray)
                $this->reSpecifyMetro();

        }
    }


    public function urlImageCropShow()
    {
        //найти модель с записями где находится картинка
        $mediaFile=MediaFile::model()->findByAttributes(array('object_id'=>$this->id, 'model_id'=>'Discount'));
        if($mediaFile===null)
            return '/upload/mediaFiles/no_image_310x205.jpg';

        //если акция наша - то один путь для кропа. если не наша - то другой
        $imgCropPath = ($this->our) ? self::PATH_OUR_IMG_CROP : self::PATH_XML_IMG_CROP;

        $path = $imgCropPath . '/' . $this->preNameCrop() . $mediaFile->name;
        if (file_exists('./' . $path )) //проверка на существование кропа
            return '/'. $path;
        //если нет - то делаем кроп
        if (file_exists('./' . $mediaFile->getHref())) //проверка на существование картинки для кропа
        return $this->createCropImage($mediaFile->getHref(), $mediaFile->name, $imgCropPath);
    }


    public function createCropImage($href, $name, $imgCropPath)
    {
        //обращаемся к модели mediafile (через behaviors) и получаем путь с именем.
        $image = Yii::app()->image->load(YiiBase::getPathOfAlias('webroot') . $href);
        //делаем crop c нужными пропорциями
        if  ( $image->width / $image->height > self::IMG_CROP_WIDTH / self::IMG_CROP_HEIGHT )
        {
            $image->resize(NULL, self::IMG_CROP_HEIGHT)->crop(self::IMG_CROP_WIDTH, self::IMG_CROP_HEIGHT, 'top')->quality(75);
        }
        else
        {
            $image->resize(self::IMG_CROP_WIDTH, NULL)->crop(self::IMG_CROP_WIDTH, self::IMG_CROP_HEIGHT, 'top')->quality(75);
        }

        $path =   $imgCropPath . '/' . $this->preNameCrop() .  $name;
        $image->save('./' . $path);
        list($pathCrop, $nameCrop) = FileSystemHelper::moveToVault($path, $imgCropPath, true);
        return '/' . $pathCrop . '/' . $nameCrop;
    }


    //приставка в имени crop файла. т.к. имя кроп файла = приставка + само имя
    private function preNameCrop()
    {
        return self::IMG_CROP_WIDTH . 'x' . self::IMG_CROP_HEIGHT . '_crop_'; //приставка 310x205_crop_
    }

    /*
     * Удаляет все текущие метро и записывает новые, которые лежат в metrosarray
     */
    private function reSpecifyMetro()
    {
        //удаляем все сущ. метро
        DiscountMetro::model()->deleteAll('discount_id=:id',array(':id'=>$this->id));
        //добавляем новые из массива $this->metrosarray
        foreach($this->metrosarray as $k=>$v)
        {
            $metro=new DiscountMetro();
            $metro->discount_id=$this->id;
            $metro->metro_id=$v;
            $metro->save();
        }
    }
    /**
     * @return array customized attribute labels (name=>label)
     */

/*
    public function attributeLabels()
    {
        return array_merge(
            parent::attributeLabels(),
            array(
                'datebs' => 'Дата окончания продаж купонов (через геттер)'
            )
        );
    }
*/

}
