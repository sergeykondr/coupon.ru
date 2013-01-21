<?
class XmlModule extends WebModule
{
    public static $active = true;

    public $icon = 'th-list';

    public function getName()
    {
        return 'XML';
    }


    public function getDescription()
    {
        return 'XML дискаунты';
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
            'Список импорт. xml'   => Yii::app()->createUrl('/xml/xmlAdmin/manage'),
            'Импортировать xml'   => Yii::app()->createUrl('/xml/xmlAdmin/import'),
            'Экспортировать свои xml'   => Yii::app()->createUrl('/xml/xmlAdmin/export'),
        );
    }
}
