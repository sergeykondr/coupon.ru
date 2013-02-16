<?

class Metro extends ActiveRecord
{
    public $distance; //дистанция для определния расстояния до метро

    public function name()
    {
        return 'Metro';
    }


    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }


    public function tableName()
    {
        return 'metro';
    }


    public function rules()
    {
        return array(
            //array('id', 'required'),
            //array('email', 'email'),
            array('id, name', 'required'),
        );

    }


    public function relations()
    {
        return array();
        /*
        $nowDate = $this->nowDate();
        return array(
            'discountCount'=>array(self::STAT, 'Discount', 'category_id',
                'condition'=>$this->queryActual()
            ),
            //'discount' => array(self::HAS_MANY, 'Discount', 'id'),
        );
        */
    }


    public function search()
    {

    }
}
