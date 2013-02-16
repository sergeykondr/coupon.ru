<?

class Test extends ActiveRecord
{
    public function name()
    {
        return 'test';
    }


    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }


    public function tableName()
    {
        return 'test';
    }


    public function rules()
    {
        return array(
            //array('id', 'required'),
            //array('email', 'email'),
            array('coordinates', 'required'),
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
