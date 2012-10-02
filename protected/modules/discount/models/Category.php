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
            'discount' => array(self::HAS_MANY, 'Discount', 'id'),
        );
    }



}
