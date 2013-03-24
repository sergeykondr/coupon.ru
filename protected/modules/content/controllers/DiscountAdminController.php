<?

class DiscountAdminController extends AdminController
{
    public static function actionsTitles()
    {
        return array(
            "manage"      => t("Управление дискаунтами"),
            "create"      => t("Добавление дискаунта"),
            "view"        => t("Просмотр дискаунта"),
            "update"      => t("Редактирование дискаунта"),
            "delete"      => t("Удаление дискаунта"),
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


    public function actionDelete($id)
    {
        $this->loadModel($id)->delete();

        if (!isset($_GET['ajax']))
        {
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        }

    }


    public function actionCreate()
    {
        $model = new Discount('our_discount');
        $form  = new Form('content.DiscountForm', $model); // в конструктор передается модель
        $this->performAjaxValidation($model);

        /*
         * Метод submitted() перед тем, как вернуть true то он заполняет модель данными.
         * (все модели которые передались в конструкторе, будут заполнены значениями из формы
         * далее модель сохраняется
         */
        $submitted = $form->submitted();
        /*
        if ($form->submitted() && $model->save())
        {
            $this->redirect(array(
                'view',
                'id' => $model->id
            ));
        }
        */

        if ($form->submitted())
        {
            $model->our = 1; //ставим отметку, что акция наша
            if ($model->save())
            {
                $this->redirect(array(
                    'view',
                    'id' => $model->id
                ));
            }

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
        $model->scenario='our_discount'; //указываем сценарий валидации
        $form  = new Form('content.DiscountForm', $model);

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


    public function actionGetJsonData($id)
    {
        echo CJSON::encode($this->loadModel($id));
    }
}
