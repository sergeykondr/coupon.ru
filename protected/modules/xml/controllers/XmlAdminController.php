<?

class XmlAdminController extends AdminController
{
    public static function actionsTitles()
    {
        return array(
            "manage"      => t("Управление import xml"),
            "create"      => t("Import xml"),
            "view"        => t("Просмотр import xml"),
            "update"      => t("Редактирование import xml"),
            //"delete"      => t("Удаление страницы"),
            "getJsonData" => t("Получение данных страницы (JSON)")
        );
    }


    public function actionManage()
    {
        $model = new Buy;
        $model->unsetAttributes();

        /*
        if (isset($_GET['Page']))
        {
            $model->attributes = $_GET['Page'];
        }
        */

        $this->render('manage', array(
            "model" => $model
        ));
    }


    public function actionCreate()
    {
        //импорт xml
        //$sxml = simplexml_load_file("http://cityradar.ru/kuponator.xml");
        //$sxml = simplexml_load_file("http://kuponator.ru/example.xml");
        $sxml = simplexml_load_file("http://cityradar.ru/kupongid.xml");
        $i=0; //счетчик для кол-ва импортированных дискаунтов
        foreach ($sxml->offers->offer as $offer)
        {

            //только купоны для Москвы
            if ($offer->region != "Москва")
                continue;

            //если id дискаунта такой уже есть, то не импортируем!!!
            if (Discount::model()->findByAttributes(array('xml_imp_id'=>$offer->id)))
                continue;

            $model = new Discount('xml_discount');
            //echo "bid: " . $offer->bid . "<br>";
            $model->xml_imp_id = $offer->id;
            $model->category_id = 10;//категория разное
            $model->our = 0; //false - акция не наша
            $model->xml_imp_url = $offer->url;
            $model->name = $offer->name;
            $model->description = $offer->description;
            $model->beginsell = (string)$offer->beginsell;
            $model->endsell = (string)$offer->endsell;
            $model->beginvalid = (string)$offer->beginvalid;
            $model->endvalid = (string)$offer->endvalid;
            $model->xml_imp_picture = $offer->picture;
            $model->price = $offer->price;
            $model->discount = $offer->discount;
            $model->discountprice = $offer->discountprice;
            $model->pricecoupon = $offer->pricecoupon;
            $model->company_name = $offer->supplier->name;
            $model->company_url = $offer->supplier->url;
            $model->company_tel = $offer->supplier->tel;
            //address. foreach
            $adres =''; // склеиваем адрес, если их несколько через ||
            foreach ($offer->supplier->addresses->address as $address )
            {
                $adres .= (!$adres) ? $address->name : '||' . $address->name;
            }
            $model->company_address = $adres;

            //сохраняем модель
            $model->save() && $i++;

            //загружаем картинки на сервер
            //$file      = CUploadedFile::getInstanceByName('file');
            //echo $file->name;
            $path = 'upload/mediaFiles/xml';
            //ивлекаем имя картинки из url
            $imgNameOriginal = substr($model->xml_imp_picture, strrpos($model->xml_imp_picture, '/') + 1);
            $imgName = FileSystemHelper::vaultResolveCollision($path, $imgNameOriginal);
            $fileImp = file_get_contents($model->xml_imp_picture, true);
            file_put_contents('./' . $path . '/' . $imgName, $fileImp); //записываем в папку

            //записываем информацию о картинке в MediaFile
            $media = new MediaFile();
            $media->object_id = $model->id;
            $media->model_id = 'Discount';
            $media->name = $imgName;
            $media->title = $imgNameOriginal;
            $media->tag = 'xml';
            $media->order = 1;
            $media->path = $path;
            $media->types = 'img';
            $media->save();


        }
       echo 'Всего было импортировано ' . $i . ' дискаунтов';
        /*
            if ($form->submitted() && $model->save())
            {
                $this->redirect(array(
                    'view',
                    'id' => $model->id
                ));
            }
            $this->render('create', array('form' => $form));
        */
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

}
