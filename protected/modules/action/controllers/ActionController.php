<?

class ActionController extends Controller
{
    public function actionView($id)
    {

        $page = Action::model()->findByPk($id);
        if (!$page)
        {
            $this->pageNotFound();
        }
        $this->render("viewPage", array(
            "page" => $page
        ));

    }

    public function actionIndex()
    {
        echo "asd";

    }

    public static function actionsTitles()
    {
        return array(
            'view'         => 'Просмотр акции',
            'index'        => 'Все акции',

        );
    }

    public function subMenuItems()
    {
        return array(
            array(
                'label' => t('Все'),
                'url'   => array('content/page/index')
            ),
            array(
                'label' => t('Красота'),
                'url'   => array('content/page/index')
            ),
            array(
                'label' => t('Здоровье'),
                'url'   => array('content/page/index')
            ),
            array(
                'label' => t('Еда'),
                'url'   => array('content/page/index')
            ),
            array(
                'label' => t('Развлечения'),
                'url'   => array('content/page/index')
            ),
            array(
                'label' => t('Отдых'),
                'url'   => array('content/page/index')
            ),
            array(
                'label' => t('Товары'),
                'url'   => array('content/page/index')
            ),
            array(
                'label' => t('Фото'),
                'url'   => array('content/page/index')
            ),
            array(
                'label' => t('Обучение'),
                'url'   => array('content/page/index')
            ),
            array(
                'label' => t('Авто'),
                'url'   => array('content/page/index')
            ),
            array(
                'label' => t('Прочее'),
                'url'   => array('content/page/index')
            ),
            array(
                'label'   => Yii::app()->user->isGuest ?: t('Ваши') . '(' . Page::model()->count('user_id = ' . Yii::app()->user->id) . ')',
                'url'     => array('/page/user/' . Yii::app()->user->id),
                'visible' => !Yii::app()->user->isGuest
            )
        );
    }

}

