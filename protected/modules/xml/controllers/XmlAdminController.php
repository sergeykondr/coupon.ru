<?

class XmlAdminController extends AdminController
{
    public static function actionsTitles()
    {
        return array(
            "view"        => t("Просмотр import xml"),
            "manage"      => t("Управление import xml"),
            "create"      => t("Import xml"),
            "view2"        => t("Просмотр import xml"),
            "update"      => t("Редактирование import xml"),
            //"delete"      => t("Удаление страницы"),
            "getJsonData" => t("Получение данных страницы (JSON)")
        );
    }


    //показываем импортированные xml
    public function actionManage()
    {
        $model = new Discount('search_xml');
        $model->unsetAttributes();

        if (isset($_GET['Discount']))
        {
            $model->attributes = $_GET['Discount'];
        }
        //application.modules.content.views.discountAdmin.manage
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

            //указываем ближайшие метро
            // функционал в разработке

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
       echo $id;
    }


    public function actionView2($id)
    {
        $model = Discount::model()->findByPk($id);
        $this->render('view', array('model' => $model));
    }

    public function actionUpdate($id)
    {
        $model = Discount::model()->with('metrosRell', 'metros')->findByPk($id);
        $model->scenario='xml_discount'; //указываем сценарий валидации
        $form  = new Form('content.DiscountForm', $model);
        $this->performAjaxValidation($model);

        /*
        if(isset($_POST['metrosRell']))
            $model->metrosarray = $_POST['metrosRell'];
        */

        if ($form->submitted() && $model->save())
        {
            $this->redirect(array(
                'view2',
                'id' => $model->id
            ));
        }

        $this->render('application.modules.content.views.discountAdmin.update', array(
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
