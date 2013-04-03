<?

class Category extends ActiveRecord
{
    public function name()
    {
        return 'Category';
    }


    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }


    public function tableName()
    {
        return 'category';
    }


    public function rules()
    {
        return array(
            array(
                'name', 'required'
            ),
            array(
                'text', 'safe'
            ),
            array(
                'name, url',
                'safe',
                'on'=>'search'
            ),
        );
    }


    public function behaviors()
    {
        return CMap::mergeArray(
            parent::behaviors(),
            array(
                'MetaTag' => array('class' => 'application.components.activeRecordBehaviors.MetaTagBehavior'),
            )
        );
    }


    public function relations()
    {
        return array(
            'discountCount'=>array(self::STAT, 'Discount', 'category_id',
                               'condition'=>$this->queryActual()
            ),
            'discount' => array(self::HAS_MANY, 'Discount','id'),
        );
    }


    //условие актуальных акций
    public function queryActual()
    {
        return "DATE(beginsell) <= '" . $this->nowDate() ."' AND DATE(endsell) >= '".$this->nowDate(). "'";
    }


    //текущая дата
    public function nowDate()
    {

        return date('Y-m-d H:i:s',time());
    }


    public function search()
    {
        $criteria = new CDbCriteria;
        $criteria->compare('name', $this->id, true);
        $criteria->compare('url', $this->url, true);

        return new ActiveDataProvider(get_class($this), array(
            'criteria'   => $criteria,
            'pagination' =>array(
                'pageSize' => 30
            )
        ));
    }

}
