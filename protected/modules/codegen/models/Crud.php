<?php
/** 
 * 
 * !Attributes - атрибуты БД
 * @property  $class
 * @property  $genetive
 * @property  $instrumental
 * @property  $accusative
 * 
 */

class Crud extends FormModel
{
    public $class;

    public $genetive;

    public $instrumental;

    public $accusative;


    public function rules()
    {
        return array(
            array('class, genetive, instrumental, accusative', 'required')
        );
    }


}


