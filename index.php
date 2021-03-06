<?

ini_set("display_errors", 1);
error_reporting(E_ALL);
ini_set('xdebug.max_nesting_level', 1000);

date_default_timezone_set('Europe/Moscow');

//by default in php.ini session.cookie_lifetime = 0, and it's Session expire - while open browser
ini_set('session.cookie_lifetime', 60 * 60 * 24 * 30);

define('DS', DIRECTORY_SEPARATOR);

$_SERVER['DOCUMENT_ROOT'] = dirname(__FILE__) .'/';

$_SERVER['DOCUMENT_ROOT'] = str_replace(array('\\', '/'), DS, $_SERVER['DOCUMENT_ROOT']);

if (substr($_SERVER['DOCUMENT_ROOT'], -1) != DS)
{
    $_SERVER['DOCUMENT_ROOT'] = $_SERVER['DOCUMENT_ROOT'] . DS;
}

require_once $_SERVER['DOCUMENT_ROOT'] . 'protected' . DS . 'config' . DS . 'constants.php';
require_once LIBRARIES_PATH . 'yii' . DS . 'yii.php';
require_once LIBRARIES_PATH . 'functions.php';
require_once LIBRARIES_PATH . 'debug.php';

$env = YII_DEBUG ? 'development' : 'production';
defined('ENV') || define('ENV', $env);
defined('CONFIG') || define('CONFIG', $env);

$config = APP_PATH . 'config' . DS . CONFIG .'.php';

Yii::createWebApplication($config)->run();

//Yii::app()->executor->migrate('up');
