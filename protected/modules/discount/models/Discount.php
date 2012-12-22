<?
/**
 * @property $id
 * @property $category_id
 * @property $numbers_buy
 * @property $price
 * @property $text
 */
class Discount extends ActiveRecord
{
    public $all_buy; //всего: накрутка + кол-во купивших реально
    public $cheat_now; //сколько надо накрутить
    public $metrosarray; //для записи массива метро
    public $jopa;
    const PAGE_SIZE = 20;


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
                'category_id, name, description,
                beginsell, endsell, beginvalid, endvalid,
                company_name, company_url, company_tel, company_address, company_coordinates, company_time,
                text, cheat, xml_kuponator',
                'required'
            ),

            array(
                'name',
                'length',
                'max' => 130
             ),

            array(
                'id, category_id, numbers_buy, price',
                'numerical',
                'integerOnly' => true
            ),

            array(
                'metros', 'metrosvalid', //в DiscountForm это поле есть, но мы тамже его принудительно переименовали.
            ),
            array(
                'metrosarray', 'required', // для него нет label ошибки (потому что в конструкторе форм по нормальному это поле не было объявлено)
            ),
        );
    }


    public function metrosvalid($attributes,$params)
    {
        if ($this->metrosarray == '')
        $this->addError('metros', 'Заполните метро'); //пользуемся label у metros для вывода ошибки
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
        $interval = $date1->diff($date2);
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
        //$userCriteria = /// нужный criteria для юзера
        $criteria=new CDbCriteria;
        $criteria->select='*, floor((cheat / ((UNIX_TIMESTAMP( endsell ) - UNIX_TIMESTAMP( beginsell ))/60/60)) * ((UNIX_TIMESTAMP( NOW() ) - UNIX_TIMESTAMP( beginsell ))/60/60)) as cheat_now, (floor((cheat / ((UNIX_TIMESTAMP( endsell ) - UNIX_TIMESTAMP( beginsell ))/60/60)) * ((UNIX_TIMESTAMP( NOW() ) - UNIX_TIMESTAMP( beginsell ))/60/60)) + numbers_buy) as all_buy';
        $criteria->condition='DATEDIFF( endsell, beginsell ) >1';
        $this->getDbCriteria()->mergeWith($criteria);
        parent::beforeFind();
    }


    public function search()
    {
        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id, true);
        $criteria->compare('category_id', $this->category_id, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('numbers_buy', $this->numbers_buy, true);
        $criteria->compare('price', $this->price, true);
        $criteria->compare('text', $this->text, true);

        return new ActiveDataProvider(get_class($this), array(
            'criteria'   => $criteria,
            'pagination' =>array(
                'pageSize' => self::PAGE_SIZE
            )
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


        // при необходимости можно обратиться к атриубуту =$this->jopa
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
