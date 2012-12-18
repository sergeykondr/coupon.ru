<?

class DiscountAdminController extends AdminController
{
    public static function actionsTitles()
    {
        return array(
            "manage"      => t("Управление дискаунтами"), //new
            "create"      => t("Добавление дискаунта"), //new
            "view"        => t("Просмотр дискаунта"), //new
            "update"      => t("Редактирование дискаунта"), //new
            "getJsonData" => t("Получение данных страницы (JSON)")
        );
    }


    public function actionManage()
    {
        $model = new Discount('search');
        $model->unsetAttributes();

        if (isset($_GET['Discount']))
        {
            $model->attributes = $_GET['Discount'];
        }

        $this->render('manage', array(
            "model" => $model
        ));
    }


    public function actionCreate()
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
        $model = Discount::model()->findByPk($id);
        $this->render('view', array('model' => $model));

    }


    public function actionUpdate($id)
    {
        $model = Discount::model()->with('metrosRell', 'metros')->findByPk($id);
        $form  = new Form('content.DiscountForm', $model);
        $this->performAjaxValidation($model);
        if(isset($_POST['metrosRell']))
            $model->metrosarray = $_POST['metrosRell'];
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


    public function actionGetJsonData($id)
    {
        echo CJSON::encode($this->loadModel($id));
    }
}
