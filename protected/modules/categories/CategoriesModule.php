<?
class CategoriesModule extends WebModule
{
    public static $active = true;

    public $icon = 'folder-open';

    public function getName()
    {
        return 'Категории';
    }


    public function getDescription()
    {
        return 'Категории';
    }


    public function getVersion()
    {
        return '1.0';
    }


    public function init()
    {

        $this->setImport(array(
            'codegen.models.*',
            'codegen.portlets.*',
        ));
    }


    public function adminMenu()
    {
        return array(
            'Список категорий'   => Yii::app()->createUrl('/categories/categoriesAdmin/manage'),
            //'Импортировать xml'   => Yii::app()->createUrl('/xml/xmlAdmin/create'),

        );
    }
}
