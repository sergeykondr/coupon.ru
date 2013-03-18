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
    public $all_buy; //всего: накрутка + кол-во купивших реально - из as
    public $xcoord; //коодинаты select as. для чтения координат
    public $ycoord; //коодинаты select as. для чтения координат
    public $cheat_now; //сколько надо накрутить
    public $metrosarray; //для записи массива метро
    public $coord_write; //массив кординат для записи
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
                company_time,
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
                'on'=>'our_discount, xml_discount',
             ),

            array(
                'price, discount, discountprice, pricecoupon',
                'numerical',
                'on'=>'our_discount, xml_discount'
            ),

            array(
                'category_id, numbers_buy',
                'numerical',
                'integerOnly' => true,
                'on'=>'our_discount'
            ),

            array(
                'metros', 'metrosvalid', //в DiscountForm это поле есть, но мы тамже его принудительно переименовали.
                'on'=>'our_discount',
            ),

            array(
                'coord_write', 'coordsvalid', 'our'=>$this->our,
                'on'=>'xml_discount, our_discount',
            ),

            array(
                //нужно чтоб записывалось сюда при ред. нашего дискаута и xml дискаунта. Можно ли тут делать атрибут safe
                'metrosarray', 'safe', // для него нет label ошибки (потому что в конструкторе форм по нормальному это поле не было объявлено)
                //'on'=>'our_discount',
            ),

            array(
                'id, name, beginsell, xml_imp_id, xml_imp_url', 'safe',
                'on'=> 'search'
            ),
        );
    }


    public function metrosvalid($attributes,$params)
    {
        if ($this->metrosarray == '')
        $this->addError('metros', 'Заполните метро'); //пользуемся label у metros для вывода ошибки
    }


    public function coordsvalid($attributes,$params)
    {

        // на входе: "long, lat". преобразовываем строку в массив
        $coords = explode(",", $this->coord_write);

        if ($coords[0] <40 and $coords[0] > 35 and  $coords[1]<58 and $coords[1]>54 )
        {
            $this->coord_write = array($coords[0],$coords[1]);
        }
        else
        {
            if ($params['our'])
            {
                $this->addError($attributes, 'Введите коодинаты правильно!');
            }
            $this->coord_write = NULL; //если координаты не правильные - не записываем их
        }
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

        // select xcoord и ycoord
        $criteria=new CDbCriteria;
        $criteria->select='*, X(t.company_coordinates) AS xcoord, Y(t.company_coordinates) AS ycoord';
        $this->getDbCriteria()->mergeWith($criteria);
        parent::beforeFind();

        //выбираем только актуальные акции и упорядочиваем их по убыванию кол-ва купивших с учетом накрутки
        /*
        $criteria=new CDbCriteria;
        $criteria->select='*, floor((cheat / ((UNIX_TIMESTAMP( endsell ) - UNIX_TIMESTAMP( beginsell ))/60/60)) * ((UNIX_TIMESTAMP( NOW() ) - UNIX_TIMESTAMP( beginsell ))/60/60)) as cheat_now, (floor((cheat / ((UNIX_TIMESTAMP( endsell ) - UNIX_TIMESTAMP( beginsell ))/60/60)) * ((UNIX_TIMESTAMP( NOW() ) - UNIX_TIMESTAMP( beginsell ))/60/60)) + numbers_buy) as all_buy';
        $criteria->condition='DATEDIFF( endsell, beginsell ) >1';
        $this->getDbCriteria()->mergeWith($criteria);
        parent::beforeFind();
        */
    }


    public function afterFind()
    {

        // select xcoord и ycoord
        $this->coord_write = $this->ycoord . ',' . $this->xcoord;
        parent::afterFind();

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
        $criteria->compare('xml_imp_id', $this->xml_imp_id, true);
        $criteria->compare('xml_imp_url', $this->xml_imp_url, true);

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

    public function getCoords()
    {

        return "$this->xcoord ,asdf $this->ycoord";
    }

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


    public function urlImageCropShow()
    {
        //найти модель с записями где находится картинка
        $mediaFile=MediaFile::model()->findByAttributes(array('object_id'=>$this->id, 'model_id'=>'Discount'));
        if($mediaFile===null)
            return '/upload/mediaFiles/no_image_310x205.jpg';

        //если акция наша - то один путь для кропа. если не наша - то другой
        //$imgCropPath = ($this->our) ? self::PATH_OUR_IMG_CROP : self::PATH_XML_IMG_CROP;
        //path: upload/mediaFiles/xml/ed
        //path: upload/mediaFiles/b2


        //$path = $imgCropPath . '/' . $this->preNameCrop() . $mediaFile->name;
        $pathCrop = $mediaFile->path . '/'. $this->preNameCrop() . $mediaFile->name;
        if (file_exists('./' . $pathCrop )) //проверка на существование кропа
            return '/'. $pathCrop;
        //если нет - то делаем кроп
        if (file_exists('./' . $mediaFile->getHref())) //проверка на существование картинки для кропа
        return $this->createCropImage($mediaFile->getHref(), $mediaFile->name, $mediaFile->path);
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
        if ($image->save('./' . $path))
            return '/' . $path;
        //list($pathCrop, $nameCrop) = FileSystemHelper::moveToVault($path, $imgCropPath, true);

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


    public function beforeSave()
    {
        if (parent::beforeSave())
        {
            if (is_array($this->coord_write) )
            {
                //$model->coord_write = $coords;
                //$x = $this->coord_write[1];
                //$y = $this->coord_write[0];
                list($long, $lat) = $this->coord_write;
                $this->company_coordinates = new CDbExpression("GEOMFROMTEXT(  \"POINT($lat $long)\", 0 )"); //координаты заданы в правильных рамках
            }
            else
            {
                //$this->coord_write =; //если координаты не определены или не правильные - не записываем их
                $this->company_coordinates =  NULL;
            }


            return true;
        }
        /*
        if ($this->coord_write[0] <40 and $coords[0] > 35 and  $coords[1]<58 and $coords[1]>54 )
        {
            $model->coord_write = $coords;
        }
        else
        {
            $model->coord_write = NULL; //если координаты не правильные - не записываем их
        }
        */

        //преобразование массива в запрос
        //$this->company_coordinates = 234234;//new CDbExpression("GEOMFROMTEXT(  \"POINT($this->coord_write[1] $this->coord_write[0] )\", 0 )"); //координаты заданы в правильных рамках

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
