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
        return array(
            'discountCount'=>array(self::STAT, 'Discount', 'category_id',
                                'condition'=>$this->queryActual()
            ),
        );
    }


    //условие актуальных акций
    public function queryActual()
    {
        return "DATE(beginsell) <= '" . $this->nowDate() ."' AND DATE(endsell) >= '".$this->nowDate(). "'";
    }


    //текущая дата
    private function nowDate()
    {

        return date('Y-m-d H:i:s',time());
    }


}
