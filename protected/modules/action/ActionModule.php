<?

class ActionModule extends WebModule
{
	public static $active = true;


    public static function name()
    {
        return 'action';
    }


    public static function description()
    {
        return 'Акции';
    }


    public static function version()
    {
        return '1.0';
    }


	public function init()
	{
		$this->setImport(array(
			'action.models.*',
			'action.portlets.*',
		));
	}


    public function adminMenu()
    {
        return array(
        );
    }


    public function routes()
    {
        return array(
            '/action/<id:\d+>'  => 'action/action/view',
            //'/page/<id:\d+>'             => 'content/page/view',

        );
    }
}
