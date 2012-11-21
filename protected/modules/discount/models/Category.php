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

    }


    public function relations()
    {
        $nowDate = $this->nowDate();
        return array(
            'discountCount'=>array(self::STAT, 'Discount', 'category_id',
                                'condition'=>$this->queryActual()
            ),
            //'discount' => array(self::HAS_MANY, 'Discount', 'id'),
        );
    }


    public function queryActual()
    {
        return "DATE(beginsell) <= '" . $this->nowDate() ."' AND DATE(endsell) >= '".$this->nowDate(). "'";
    }


    private function nowDate()
    {
        //текущая дата
        return date('Y-m-d H:i:s',time());
    }


}
