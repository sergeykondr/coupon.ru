<?
class BuyModule extends WebModule
{
    public static $active = true; //?

    public $icon = 'gift';

    public function getName()
    {
        return 'Покупки';
    }


    public function getDescription()
    {
        return 'Совершенные покупки';
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
            'Список покупок'   => Yii::app()->createUrl('/buy/buyAdmin/manage'),

        );
    }
}
