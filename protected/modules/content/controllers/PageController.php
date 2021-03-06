<?

class PageController extends Controller
{
    public function filters()
    {
        return array_merge(
            parent::filters(),
            array(
                array(
                    'application.modules.tags.components.filters.ViewsSaveFilter',
                    'model_id' => 'Page'
                )
            )
        );
    }


    public static function actionsTitles()
    {
        return array(
            'view'         => 'Просмотр топика',
            'main'         => 'Главная страница',
            'create'       => 'Новый топик',
            'update'       => 'Редактирование топика',
            'index'        => 'Все топики',
            'userPages'    => 'Топики пользователя',
            'sectionPages' => 'Топики раздела',
            'tagPages'     => 'Топики тега'
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
            'label' => $categories[10]->name, //ссылка на главную страницу
            'url'   => array('/discount/discount/index')
        );
        //записываем всё остальное меню
        //указываем экшен, который обрабатывает запросы
        $urlPart = '/discount/discount/category';
        foreach ($categories as $name)
        {
            if ($name->id == 11) //пропускаем ссылку на главную страницу
                continue;
            //обращаемся к модели категорий по id, узнаем кол-во акций
            $count = Category::model()->with('discountCount')->findByPk($name->id);
            $menu[] = array(
                'label' => $name->name.' ('.$count->discountCount.')',
                'url'   => array($urlPart, 'cat' => $name->url )
            );
        }
        return $menu;
    }


    public function sidebars()
    {
        return array(
            array(
                'actions'  => array('create', 'update'),
                'sidebars' => array(
                    array(
                        'widget',
                        'application.modules.content.portlets.SectionCreateSidebar',
                    ),
                    array(
                        'widget',
                        'application.modules.tags.portlets.TagCreateSidebar',
                    ),
                    array(
                        'partial',
                        'application.modules.content.views.page._sidebarFormNotices'
                    )
                )
            ),
            array(
                'actions'  => array('index'),
                'sidebars' => array(
                    array(
                        'widget',
                        'application.modules.content.portlets.PageSectionsSidebar'
                    ),
                    array(
                        'widget',
                        'application.modules.comments.portlets.CommentsSidebar',
                    ),
                    /*array(
                        'widget',
                        'application.modules.content.portlets.NavigatorSidebar',
                    ),*/
                )
            ),
            array(
                'actions'  => array('view'),
                'sidebars' => array(
                    array(
                        'widget',
                        'application.modules.content.portlets.PageInfoSidebar'
                    )
                )
            ),
            array(
                'actions'  => array('userPages'),
                'sidebars' => array(
                    array(
                        'widget',
                        'application.modules.content.portlets.userPagesSidebar'
                    )
                )
            ),
        );
    }


    public function actionView($id)
    {
        $page = Page::model()->findByPk($id);
        if (!$page)
        {
            $this->pageNotFound();
        }
        $this->setMetaTags($page);
        //Yii::app()->clientScript->registerMetaTag;


        $this->render("view", array(
            "page" => $page
        ));
        //
    }


    public function actionMain()
    {
        $this->render('main', array());
    }


    public function actionCreate()
    {
        $model = new Page(ActiveRecord::SCENARIO_CREATE);
        $form  = new Form('content.PageCForm', $model);

        if ($form->submitted() && $model->save())
        {
            $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('create', array(
            'form' => $form
        ));
    }


    public function actionUpdate($id)
    {
        echo "111";
        $model = Page::model()->findByPk($id);
        if (!$model)
        {
            $this->pageNotFound();
        }

        $form = new Form('content.PageCForm', $model);

        if ($form->submitted() && $model->save())
        {
            $model->updateSectionsRels();
            $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('update', array(
            'form' => $form
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


    public function actionSectionPages($section_id)
    {
        $section = PageSection::model()->findByPk($section_id);
        if (!$section)
        {
            $this->pageNotFound();
        }

        $this->page_title = $section->name;

        $section_rel_table = PageSectionRel::model()->tableName();

        $criteria = new CDbCriteria();
//        $criteria->compare('t.status', Page::STATUS_PUBLISHED);
//        $criteria->with  = array('tags', 'sections');
//        $criteria->order = 't.date_create DESC';
//        $criteria->join  = "INNER JOIN {$section_rel_table}
//                                ON  {$section_rel_table}.section_id = {$section_id}";
//
        $data_provider = new CActiveDataProvider('Page', array(
            'criteria'   => $criteria,
            'pagination' => array(
              'pageSize' => '10'
            )
        ));
        //count($data_provider->getTotalItemCount()); die;
        $this->render('index', array(
            'data_provider' => $data_provider,
            'section'       => $section
        ));
    }


    public function actionTagPages($tag_name)
    {
        $tag = Tag::model()->findByAttributes(array('name' => $tag_name));
        if (!$tag)
        {
            $this->pageNotFound();
        }

        $this->page_title = t('Страницы с тегом') . ' ' . $tag->name;

        $tag_rel_table = TagRel::model()->tableName();

        $criteria = new CDbCriteria();
        $criteria->compare('t.status', Page::STATUS_PUBLISHED);
        $criteria->with = array('tags', 'sections');

        $criteria->addCondition("t.id IN (
            SELECT object_id FROM {$tag_rel_table}
                WHERE tag_id = {$tag->id} AND
                       model_id  = 'Page'
        )");

        $data_provider = new CActiveDataProvider('Page', array(
            'criteria'   => $criteria,
            'pagination' => array(
                'pageSize' => '10'
            )
        ));

        $this->render('index', array(
            'data_provider' => $data_provider,
            'tag'           => $tag
        ));
    }


    public function actionUserPages($user_id, $widget = 'list')
    {
        $widgets = self::displayWidgets();
        if (!isset($widgets[$widget]))
        {
            $this->pageNotFound();
        }

        $user = User::model()->findByPk($user_id);
        if (!$user)
        {
            $this->pageNotFound();
        }

        $criteria = new CDbCriteria();
        $criteria->with  = array('tags');
        $criteria->order = 'date_create DESC';

        $is_owner = !Yii::app()->user->isGuest && (Yii::app()->user->id == $user->id);

        if ($is_owner)
        {
            $this->page_title = t('Ваши страницы');

            $model = new Page(ActiveRecord::SCENARIO_SEARCH);
            $model->unsetAttributes();

            if (isset($_GET['Page']))
            {
                $model->attributes = $_GET['Page'];
            }

            $criteria->compare('status', $model->status);
        }
        else
        {
            $this->page_title = t("Страницы пользователя " . "(" . $user->name . ")");

            $criteria->compare('status', Page::STATUS_PUBLISHED);
        }

        $data_provider = new CActiveDataProvider('Page', array(
            'criteria'   => $criteria,
            'pagination' => array(
              'pageSize' => '10'
            )
        ));

        $this->render('userPages', array(
            'data_provider' => $data_provider,
            'widget'        => $widget,
            'user'          => $user,
            'model'         => isset($model) ? $model : null
        ));
    }


    public static function displayWidgets()
    {
        return array(
            'list' => t('показывать списком'),
            'grid' => t('показывать таблицей')
        );
    }   
}
