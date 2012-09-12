<?php
/** 
 * 
 * !Attributes - атрибуты БД
 * @property string  $id
 * @property string  $user_id
 * @property string  $object_id
 * @property string  $model_id
 * @property integer $value
 * @property string  $date_create
 * 
 * !Accessors - Геттеры и сеттеры класа и его поведений
 * @property         $href
 * @property         $errorsFlatArray
 * 
 */

class Rating extends ActiveRecord
{
    const PAGE_SIZE = 20;



    public function name()
    {
        return 'Рейтинг';
    }


    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }


    public function tableName()
    {
        return 'ratings';
    }


    public function rules()
    {
        return array(
            array(
                'user_id, object_id, model_id, value',
                'required'
            ),
            array(
                'model_id',
                'length',
                'max' => 50
             ),

            array(
                'user_id',
                'NotObjectAuthorValidator'
            ),
        );
    }


    public function relations()
    {
        return array(
        );
    }


    public function search()
    {
        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id, true);
        $criteria->compare('user_id', $this->user_id, true);
        $criteria->compare('object_id', $this->object_id, true);
        $criteria->compare('model_id', $this->model_id, true);
        $criteria->compare('value', $this->value, true);
        $criteria->compare('date_create', $this->date_create, true);

        return new ActiveDataProvider(get_class($this), array(
            'criteria'   => $criteria,
            'pagination' =>array(
                'pageSize' => self::PAGE_SIZE
            )
        ));
    }


    public function getHref()
    {
        return Yii::app()->createUrl('/main/rating/view', array('id' => $this->id));
    }


    public function uploadFiles()
    {
        return array(
        );
    }
}
