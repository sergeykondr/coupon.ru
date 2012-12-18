<?

class DiscountController extends Controller
{

    public static function actionsTitles()
    {
        return array(
            'view'         => 'Просмотр акции',
            'index'        => 'Все акции',
            'views'         => 'Просмотр акции',
            'category' => 'Просмотр категорий',
            'buy' => 'Покупка акции',

        );
    }


    public function actionView($id)
    {
        $page = Discount::model()->with('category', 'metros', 'metrosRell')->findByPk($id);
        if (!$page)
        {
            $this->pageNotFound();
        }

        //узнать в какой категории акция
        //$similar = Discount::model()->with('category')->findByPk($id);
        $similars = new CActiveDataProvider('Discount', array(
            'criteria' => array(
                'order'     => 'all_buy DESC',
                'limit'=>5,
                'condition'=>'category_id = ' . $page->category_id . ' AND id <>' . $page->id, //выводим из тойже категории + исключаем текущий дискаунт
            ),
            'pagination' => array(
                'pageSize' => '5'
            )
        ));
        $similars->setPagination(false);

       // echo $page->category->name;
       // dump($page->category->attributes);

        $metro = Metro::model()->findAll(
            array('order' => 'name'));

        $this->render("viewPage", array(
            "page" => $page, "similars" => $similars, "metro" => $metro
        ));
    }


    public function actionBuy($id)
    {
        $model=new Buy;
        if(isset($_POST['Buy']))
        {
            $model->attributes=$_POST['Buy'];
            if($model->validate())
            {
                $model->discount_id = $id;
                $model->date = $model->nowDate();

                $page = Discount::model()->with('category')->findByPk($id);

                /*проверка покупался ли купон ранее
                если запись с таким e-mail и discountid существует,
                то просто показываем купон, без заведения новой строчки в БД buy (покупка)
                */
                $buyIsExist=Buy::model()->find(array(
                    'condition'=>'email=:email AND discount_id=:discountID',
                    'params'=>array(':email'=>$model->email,':discountID'=>$id),
                ));
                //если такой покупки еще нет, то делаем соответствующие записи
                //
                if (!$buyIsExist)
                {
                    //добавляем новую покупку.
                    $model->save();
                    $buyCurrentId = $model->id; //узнаем id новой покупки
                    //счетчик купивших в discount +1
                    if (!$page)
                    {
                        $this->pageNotFound();
                    }
                    $page->numbers_buy ++;
                    $page->save();

                }
                else
                {
                    $buyCurrentId = $buyIsExist->id; //узнаем id старой покупки
                }

                //узнать id текущей покупки
                //echo $model->id;

                //генерируем купон
                $similars = Discount::model()->findByPk($id);
                if (!$page)
                {
                    $this->pageNotFound();
                }
                $this->renderPartial("viewCoupon", array(
                    "page" => $page, "similars" => $similars, "buyCurrentId" => $buyCurrentId,
                ));
            }
        }

        //$this->redirect(array('site/index'));
        /*
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
        */

    }


    public function actionIndex()
    {
        //$this->page_title = '';
        $this->pageTitle = 'Гланая страница';

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
                'pageSize' => '30'
            )
        ));


        $this->render('index', array(
            'data_provider' => $data_provider,
        ));

    }

    public function actionCategory($cat)
    {
        //получаем критерию актуальных акций
        //$cr= ;

        $modelCat=Category::model()->findByAttributes(array('url'=>$cat));

        if($modelCat===null)
            throw new CHttpException(404,'Страница не найдена');

        $activeDataProvider = new CActiveDataProvider(Discount::model()->with('category')->inCategory($modelCat->id), array(
                'criteria' => array(
                    'order'     => 'beginsell DESC',
                    'condition'=>Category::model()->queryActual(),
                ),
                'pagination' => array(
                    'pageSize' => '30'
                )
            )
        );

        $this->render('index', array(
            'data_provider' => $activeDataProvider,
        ));


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

