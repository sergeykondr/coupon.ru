<?

class CategoriesAdminController extends AdminController
{
    public static function actionsTitles()
    {
        return array(
            "view"        => t("Просмотр категории"), //??? не работает!!!
            "manage"      => t("Управление категориями"),
            "create"      => t("Import xml"),
            "update"      => t("Редактирование категории"),
            //"delete"      => t("Удаление страницы"),
            "getJsonData" => t("Получение данных страницы (JSON)")
        );
    }


    //показываем импортированные xml
    public function actionManage()
    {
        $model = new Category('search');
        $model->unsetAttributes();

        if (isset($_GET['Category']))
        {
            $model->attributes = $_GET['Category'];
        }

        $this->render('manage', array(
            "model" => $model
        ));
    }


    public function actionCreate()
    {

    }


    public function actionView($id)
    {
       echo $id;
    }




    public function actionUpdate($id)
    {
        $model = Category::model()->findByPk($id);
        $form  = new Form('categories.CategoryForm', $model);
        $this->performAjaxValidation($model);

        if ($form->submitted() && $model->save())
        {
            $this->redirect(array(
                'manage',
                //'id' => $model->id
            ));
        }

        $this->render('update', array(
            'form' => $form,
        ));
    }


    public function actionDelete($id)
    {
        echo 'не предусмотрено';
    }

}
