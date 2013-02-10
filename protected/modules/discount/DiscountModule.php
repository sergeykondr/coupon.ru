<?

class DiscountModule extends WebModule
{
	public static $active = true;


    public static function name()
    {
        return 'discount';
    }


    public static function description()
    {
        return 'discount';
    }


    public static function version()
    {
        return '1.0';
    }


	public function init()
	{
		$this->setImport(array(
			'discount.models.*',
			'discount.portlets.*',
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
            '/'                   => 'discount/discount/index',
            '/discount/<id:\d+>'  => 'discount/discount/view',
            '/category/<cat:[a-zA-Z]+>'  => 'discount/discount/category',
            '/discount/buy/<id:\d+>'  => 'discount/discount/buy',
            '/discount/xmlbuy/<id:\d+>'  => 'discount/discount/xmlbuy',
        );
    }
}
