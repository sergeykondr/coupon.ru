<?

class DiscountController extends Controller
{
    public function actionView($id)
    {
        $page = Discount::model()->with('category')->findByPk($id);
        if (!$page)
        {
            $this->pageNotFound();
        }

       // echo $page->category->name;
       // dump($page->category->attributes);

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
            'views'         => 'Просмотр акции',

        );
    }

    public function subMenuItems()
    {
        return array(
            array(
                'label' => t('Все'),
                'url'   => array('discount/category/all')
            ),
            array(
                'label' => t('Красота'),
                'url'   => array('discount/category/beauty')
            ),
            array(
                'label' => t('Здоровье'),
                'url'   => array('discount/category/health')
            ),
            array(
                'label' => t('Еда'),
                'url'   => array('discount/category/food')
            ),
            array(
                'label' => t('Развлечения'),
                'url'   => array('discount/category/entertainment')
            ),
            array(
                'label' => t('Отдых'),
                'url'   => array('discount/category/rest')
            ),
            array(
                'label' => t('Товары'),
                'url'   => array('discount/category/beauty')
            ),
            array(
                'label' => t('Фото'),
                'url'   => array('discount/category/photo')
            ),
            array(
                'label' => t('Обучение'),
                'url'   => array('discount/category/education'),
                'active'=>false
            ),
            array(
                'label' => t('Авто'),
                'url'   => array('discount/category/auto')
            ),
            array(
                'label' => t('Прочее'),
                'url'   => array('discount/category/other')
            ),
            array(
                'label'   => Yii::app()->user->isGuest ?: t('Ваши') . '(' . Page::model()->count('user_id = ' . Yii::app()->user->id) . ')',
                'url'     => array('/page/user/' . Yii::app()->user->id),
                'visible' => !Yii::app()->user->isGuest
            )
        );
    }

}

