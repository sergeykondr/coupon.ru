<?

class DiscountMetro extends ActiveRecord
{
    public function name()
    {
        return 'DiscountMetro';
    }


    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }


    public function tableName()
    {
        return 'discount_metro';
    }


    public function rules()
    {
        return array(
            array('discount_id, metro_id', 'required'),
            //array('email', 'email'), //
        );

    }


    public function relations()
    {
        return array(
            'metros' => array(self::BELONGS_TO, 'metro', 'metro_id'),
        );


    }


    public function search()
    {

    }
}
