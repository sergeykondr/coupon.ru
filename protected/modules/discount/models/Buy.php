<?

class Buy extends ActiveRecord
{
    public function name()
    {
        return 'Buy';
    }


    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }


    public function tableName()
    {
        return 'buy';
    }


    public function rules()
    {
        /*
         * id
	 2	email
	 3	date
	 4	discount_id
         */
        return array(
            array('email', 'required'),
            array('email', 'email'),
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


    public function nowDate()
    {
        //текущая дата
        return date('Y-m-d H:i:s',time());
    }

    public function search()
    {
        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('date', $this->date, true);
        $criteria->compare('discount_id', $this->discount_id, true);

        return new ActiveDataProvider(get_class($this), array(
            'criteria'   => $criteria,
            'pagination' =>array(
                'pageSize' => 50
            )
        ));
    }
}
