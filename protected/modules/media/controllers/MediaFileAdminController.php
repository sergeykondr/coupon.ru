<?
class MediaFileAdminController extends AdminController
{
    public static function actionsTitles()
    {
        return array(
            "delete"       => "Удаление файла",
            "upload"       => "Скачать файл",
            "manage"       => "Скачать файл",
            "existFiles"   => "Скачать файл",
            "savePriority" => "Скачать файл",
            "updateAttr"   => "Скачать файл",
            "deleteoldpict"   => "удалить",

        );
    }


    public function actions()
    {
        return array(
            'updateAttr'   => array(
                'class'      => 'media.components.UpdateAttrAction',
                'attributes' => array(
                    'title',
                    'descr'
                )
            ),
            'savePriority' => array(
                'class' => 'media.components.SavePriorityAction',
            )
        );
    }


    protected function sendFilesAsJson($files)
    {
        $res = array();
        foreach ((array)$files as $file)
        {
            $res[] = array(
                'title'          => $file['title'] ? $file['title'] : 'Кликните для редактирования',
                'descr'          => $file['descr'] ? $file['descr'] : 'Кликните для редактирования',
                'url'            => $file['href'],
                'thumbnail_url'  => $file['icon'],
                'delete_url'     => $file['deleteUrl'],
                'delete_type'    => "post",
                'edit_url'       => $this->createUrl('/media/mediaFile/updateAttr', array(
                    'id'  => $file['id'],
                )),
                'id'             => 'File_' . $file->id,
            );
        }

        echo CJSON::encode($res);
    }


    public function actionExistFiles($model_id, $object_id, $tag)
    {
        if ($object_id == 0)
        {
            $object_id = 'tmp_' . Yii::app()->user->id;
        }

        $existFiles = MediaFile::model()->parent($model_id, $object_id)->tag($tag)->findAll();
        $this->sendFilesAsJson($existFiles);
    }


    public function actionUpload($model_id, $object_id, $tag)
    {
        if ($object_id == 0)
        {
            $object_id = 'tmp_' . Yii::app()->user->id;
        }

        $model            = new MediaFile('insert');
        $model->object_id = $object_id;
        $model->model_id  = $model_id;
        $model->tag       = $tag;

        if ($model->saveFile() && $model->save())
        {
            $this->sendFilesAsJson(array($model));
        }
        else
        {
            echo CJSON::encode(array(
                'textStatus' => $model->error
            ));

        }
    }


    public function actionSavePriority()
    {
        $ids = array_reverse($_POST['File']);

        $files = new MediaFiel('sort');

        $case = SqlHelper::arrToCase('id', array_flip($ids), 't');
        $arr  = implode(',', $ids);
        Yii::app()->db->getCommandBuilder()
            ->createSqlCommand("UPDATE {$files->tableName()} AS t SET t.order = {$case} WHERE t.id IN ({$arr})")
            ->execute();
    }


    public function actionDelete($id)
    {
        $this->loadModel($id)->delete();
    }


    public function actionUpdateAttr($id)
    {
        $model = $this->loadModel($id);

        $model->scenario = 'update';

        $this->performAjaxValidation($model);
        $attr = $_POST['attr'];
        if (isset($_POST[$attr]))
        {
            $model->$attr = trim(strip_tags($_POST[$attr]));

            if ($model->save(false))
            {
                echo $model->$attr;
            }
        }
    }


    public function actionManage()
    {
        $model = new MediaFile('search');
        $model->unsetAttributes();
        if (isset($_GET['MediaFile']))
        {
            $model->attributes = $_GET['MediaFile'];
        }

        $this->render('manage', array('model' => $model));
    }


    public function actionDeleteoldpict()
    {
        //выбираем все старые дискаунты
        $criteria=new CDbCriteria;
        $criteria->select='id';  // выбираем только поле 'title'
        //только чужие акции с истекшим сроком продажи
        $criteria->condition="our = 0 AND DATE(endsell) < '" . Yii::app()->dateFormatter->format('yyyy-MM-dd HH:mm:ss', time()) . "'";
        $oldDiscounts = Discount::model()->findAll($criteria);
        $countDel=0;
        echo 'discount id where pictured deleted:<br>';
        foreach($oldDiscounts as $discount)
        {
            echo $discount->id.'<br>';
            $discountMediaFile = MediaFile::model()->findByAttributes(array('object_id'=>$discount->id, 'model_id'=>'Discount'));
            if(!$discountMediaFile==null)
            {
                $discountMediaFile->delete();
                $countDel++;
            }
        }
        echo $countDel . ' discount pictures has been deleted';
    }





}
