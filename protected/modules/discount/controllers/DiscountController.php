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
        $this->page_title = '';

        /*
        echo '<pre>';
        print_r($menu);
        echo '</pre>';*/



        //echo $categories->name;
        //dump($categories->attributes);

        /*
        $data_provider = new CActiveDataProvider('Page', array(
            'criteria' => array(
                'condition' => "status = '" . Page::STATUS_PUBLISHED . "'",
                'order'     => 'date_create DESC',
                'with'      => array('tags')
            ),
            'pagination' => array(
                'pageSize' => '10'
            )
        ));
        */
        $data_provider = new CActiveDataProvider('Discount', array(
            'criteria' => array(
                'order'     => 'beginsell DESC',
            ),
            'pagination' => array(
                'pageSize' => '10'
            )
        ));


        $this->render('index', array(
            'data_provider' => $data_provider,
        ));

    }

    public function actionCategory($cat)
    {
        //узнаем какую категорию запрашивают
        $modelCat=Category::model()->findByAttributes(array('url'=>$cat));

        if($modelCat===null)
            throw new CHttpException(404,'Страница не найдена');

        $activeDataProvider = new CActiveDataProvider(Discount::model()->with('category')->inCategory($modelCat->id), array(
                'criteria' => array(
                    'order'     => 'beginsell DESC',
                ),
                'pagination' => array(
                    'pageSize' => '10'
                )
            )
        );

        $this->render('index', array(
            'data_provider' => $activeDataProvider,
        ));


    }

    public static function actionsTitles()
    {
        return array(
            'view'         => 'Просмотр акции',
            'index'        => 'Все акции',
            'views'         => 'Просмотр акции',
            'category' => 'Просмотр категорий',

        );
    }

    public function subMenuItems()
    {

        //узнаем категории из БД
        $categories=Category::model()->findAll();
        //массив для меню
        $menu = array();
        //записываем первое меню - 'Все' (его нет в БД)
        $menu[] = array(
            'label' => t('Все'),
            'url'   => array('/discount/discount/index')
        );
        //записываем всё остальное меню
        //первая часть url для страниц категорий
        $urlPart = '/discount/discount/category'; //'category/';
        foreach ($categories as $name)
        {
            //обращаемся к модели категорий по id, узнаем кол-во акций
            $count = Category::model()->with('discountCount')->findByPk($name->id);
            $menu[] = array(
                'label' => t($name->name.' ('.$count->discountCount.')'),
                'url'   => array($urlPart, 'cat' => $name->url )
            );
        }
        return $menu;

        /*
        return array(
            array(
                'label' => t('Все'),
                'url'   => array('/discount/discount/index')
            ),
            array(
                'label' => t('Красота'),
                'url'   => array('/discount/discount/category', 'cat'=>'beauty')
            ),

            array(
                'label' => t('Здоровье'),
                'url'   => array('/category/health')
            ),
            array(
                'label' => t('Еда'),
                'url'   => array('category/food')
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
        */

    }

}

