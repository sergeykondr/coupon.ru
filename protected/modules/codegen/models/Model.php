<?php
/** 
 * 
 * !Attributes - атрибуты БД
 * @property  $name
 * @property  $table
 * @property  $class
 * @property  $module
 * @property  $behaviors
 * 
 * !Accessors - Геттеры и сеттеры класа и его поведений
 * @property  $path
 * 
 */

class Model extends FormModel
{
    public $name;

    public $table;

    public $class;

    public $module;

    public $behaviors;

    public static $file_attributes = array('photo', 'image', 'file', 'icon');

    public static $not_required_attributes = array('id', 'date_create', 'date_update');

    public static $extra_behaviors = array(
        'Сортировка' => 'ext.sortable.SortableBehavior',
        'Мета тэги'  => 'application.components.activeRecordBehaviors.MetaTagBehavior'
    );


    public function rules()
    {
        return array(
            array('name, class, table, module', 'required'),
            //array('class', 'fileNotExists'),
            array('table', 'TableExistsValidator')
        );
    }


    public function fileNotExists($attr)
    {
        if (file_exists($this->path))
        {
            $this->addError($attr, "Файл '{$this->path}' уже существует");
        }
    }


    public function getPath()
    {
        return MODULES_PATH . $this->module . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . $this->class . '.php';
    }
}























