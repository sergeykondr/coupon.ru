<?

class PageAdminController extends AdminController
{
    public static function actionsTitles()
    {
        return array(
            "manage"      => t("Управление страницами"),
            "create"      => t("Добавление страницы"),
            "creatediscount"      => t("Добавление акции"), //new
            "view"        => t("Просмотр страницы"),
            "viewdiscount"        => t("Просмотр акции"), //new
            "update"      => t("Редактирование страницы"),
            "updatediscount"      => t("Редактирование акции"), //new
            "delete"      => t("Удаление страницы"),
            "getJsonData" => t("Получение данных страницы (JSON)")
        );
    }


    public function actionManage()
    {
        $model = new Page('search');
        $model->unsetAttributes();

        if (isset($_GET['Page']))
        {
            $model->attributes = $_GET['Page'];
        }

        $this->render('manage', array(
            "model" => $model
        ));
    }


    public function actionCreate()
    {
        $model = new Page(ActiveRecord::SCENARIO_CREATE);
        $form  = new Form('content.PageForm', $model); // в конструктор передается модель
        $this->performAjaxValidation($model);

        /*
         * Метод submitted() перед тем, как вернуть true то он заполняет модель данными.
         * (все модели которые передались в конструкторе, будут заполнены значениями из формы
         * далее модель сохраняется
         */
        if ($form->submitted() && $model->save())
        {
            $this->redirect(array(
                'view',
                'id' => $model->id
            ));
        }
        $this->render('create', array('form' => $form));
    }

    public function actionCreateDiscount()
    {
        $model = new Discount();
        $form  = new Form('content.DiscountForm', $model); // в конструктор передается модель
        $this->performAjaxValidation($model);

        /*
         * Метод submitted() перед тем, как вернуть true то он заполняет модель данными.
         * (все модели которые передались в конструкторе, будут заполнены значениями из формы
         * далее модель сохраняется
         */
        if ($form->submitted() && $model->save())
        {
            $this->redirect(array(
                'view',
                'id' => $model->id
            ));
        }
        $this->render('create', array('form' => $form));
    }

    public function actionView($id)
    {
        $model = $this->loadModel($id);

        if ($model === null)
        {
            $this->pageNotFound();
        }

        if (isset($_GET['json']))
        {
            echo CJSON::encode($model);
        }
        else
        {
            $this->render('view', array('model' => $model));
        }
    }

    public function actionViewDiscount($id)
    {
        //$model = $this->loadModel($id);
        $model = new Discount();
        $model->findByPk($id);
        $model = Discount::model()->findByPk($id);

        $this->render('viewDiscount', array('model' => $model));

    }


    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);
        $form  = new Form('content.PageForm', $model);

        $this->performAjaxValidation($model);

        if ($form->submitted() && $model->save())
        {
            $this->redirect(array(
                'view',
                'id' => $model->id
            ));
        }

        $this->render('update', array(
            'form' => $form,
        ));
    }


    public function actionDelete($id)
    {


        $this->loadModel($id)->delete();

        if (!isset($_GET['ajax']))
        {
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        }

    }


    public function actionGetJsonData($id)
    {
        echo CJSON::encode($this->loadModel($id));
    }
}
