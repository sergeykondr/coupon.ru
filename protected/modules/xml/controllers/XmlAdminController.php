<?

class XmlAdminController extends AdminController
{
    public $fileXmlLoad; //загружаемый xml файл
    public $urlsImage = array(); //массив url картинок
    public $urlsImageContent; //массив url и контента картинок
    public $cityRadar = 'http://cityradar.ru/kupongid.xml';


    public static function actionsTitles()
    {
        return array(
            "view"        => t("Просмотр import xml"),
            "manage"      => t("Управление import xml"),
            "import"      => t("Import xml"),
            "export"      => t("Export xml"),
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


    public function xmlLoad()
    {
        $this->fileXmlLoad = simplexml_load_file($this->cityRadar);
    }


    //получение url и контента картинок для несуществющих дискаунтов
    public function loadImage()
    {
        if (empty($this->fileXmlLoad))
            $this->xmlLoad();
        foreach ($this->fileXmlLoad->offers->offer as $offer)
        {
            //только купоны для Москвы
            if ($offer->region != "Москва")
                continue;
            //если id дискаунта такой уже есть, то пропускаем
            if (Discount::model()->findByAttributes(array('xml_imp_id'=>$offer->id)))
                continue;
            //url картинок
            $this->urlsImage[] = (string)$offer->picture;
        }
        //получение контента картинок
        $this->urlsImageContent = CurlHelper::multi($this->urlsImage);
    }

    public function actionImport()
    {
        $this->loadImage();
        $i = 0;
        foreach ($this->fileXmlLoad->offers->offer as $offer)
        {
            //только купоны для Москвы
            if ($offer->region != "Москва")
                continue;
            //если id дискаунта такой уже есть, то не импортируем
            if (Discount::model()->findByAttributes(array('xml_imp_id'=>$offer->id)))
                continue;

            $model = new Discount('xml_discount');
            $model->xml_imp_id = $offer->id;
            $model->category_id = 10;//категория разное
            $model->our = 0; //false - акция не наша
            $model->xml_imp_url = $offer->url;
            $model->name = $offer->name; //Название дискаунта
            $model->description = $offer->description;
            $model->beginsell = (string)$offer->beginsell;
            $model->endsell = (string)$offer->endsell;
            $model->beginvalid = (string)$offer->beginvalid;
            $model->endvalid = (string)$offer->endvalid;
            $model->xml_imp_picture = (string)$offer->picture; //лишнее?
            $this->urlsImage[] = (string)$offer->picture;
            $model->price = $offer->price;
            $model->discount = $offer->discount;
            $model->discountprice = $offer->discountprice;
            $model->pricecoupon = $offer->pricecoupon;
                $model->company_name = $offer->supplier->name;
                $model->company_url = $offer->supplier->url;
                $model->company_tel = $offer->supplier->tel;
                    //адреса компании
                    $adres =''; // склеиваем адрес, если их несколько через ||
                    foreach ($offer->supplier->addresses->address as $address )
                    {
                        $adres .= (!$adres) ? $address->name : '||' . $address->name;
                    }
                    $model->company_address = $adres;

            //сохраняем модель
            $model->save() && $i++ ;

            // указываем ближайшие метро. функционал в разработке

            //загружаем картинки
            $this->addPictureImport($model->xml_imp_picture, $model->id);

            //3) записываем SEO теги: $model->id; $offer->name; $offer->supplier->name;
            $this->addMetaTegImport($model->id, $offer->name, $offer->supplier->name);
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

    /**
     * загружаем картинки на сервер
     * @param $urlPicture - url картинки на чужом сайта
     * @param $discountId - id дискаунта в нашей базе
     */
    public function addPictureImport($urlPicture, $discountId)
    {
        $arrayUrlImage = explode("/", $urlPicture);
        $imgNameOriginal = end($arrayUrlImage);
        $imgName = FileSystemHelper::vaultResolveCollision(Discount::PATH_XML_IMG, $imgNameOriginal); //определяем уникальное имя для указанной папки
        file_put_contents(YiiBase::getPathOfAlias('webroot') . '/'. Discount::PATH_XML_IMG . '/' . $imgName , $this->urlsImageContent[$urlPicture]); //записываем в текущ. папку
        unset($this->urlsImageContent[$urlPicture]); // удаляем элемент с картинкой

        // записываем информацию о картинке в MediaFile
        $media = new MediaFile();
        $media->object_id = $discountId;
        $media->model_id = 'Discount';
        $media->name = $imgName;
        $media->title = $imgNameOriginal;
        $media->tag = 'xml';
        $media->order = 1;
        $media->path = Discount::PATH_XML_IMG;
        $media->types = 'img';
        $media->save();
    }


    //записываем сео теги
    public function addMetaTegImport($discountId, $discountName, $companyName)
    {
        $metatags = new MetaTag();
        $metatags->model_id = 'Discount';
        $metatags->object_id = $discountId;
        $metatags->title = $discountName;
        $metatags->keywords = 'москва, ' . $companyName;
        $metatags->description = $companyName . 'Купон на скидку. Отзывы об акции.';
        $metatags->save();
    }


    public function actionExport()
    {
        $criteria=new CDbCriteria;
        //$criteria->select='title';  // выбираем только поле 'title'
        $criteria->condition=Category::model()->queryActual() . 'and our = 1';
        //$criteria->params=array(':postID'=>10);
        $discountsModel = Discount::model()->findAll($criteria);

        $dom = new DomDocument('1.0',  'utf-8');

        //добавление корня - <discounts>
        $discounts = $dom->appendChild($dom->createElement('discounts'));
        //добавление элемента <operator> в <discounts>
        $operator = $discounts->appendChild($dom->createElement('operator'));
        //добавление элемента <name> в <operator>
        $operatorName = $operator->appendChild($dom->createElement('name'));
        $operatorName->appendChild($dom->createTextNode('FreeSkidka'));
        //добавление элемента <url> в <operator>
        $operatorUrl = $operator->appendChild($dom->createElement('url'));
        $operatorUrl->appendChild($dom->createTextNode('http://freeskidka.ru'));
        //добавление элемента <offers> в <discounts>
        $offers = $discounts->appendChild($dom->createElement('offers'));

        foreach ($discountsModel as $k => $v)
        {
            // НАЧАЛО цикла добавления каждого offer
            $offer = $offers->appendChild($dom->createElement('offer'));
            //добавление элемента <id> в <offer>
            $id = $offer->appendChild($dom->createElement('id'));
            $id->appendChild($dom->createTextNode($v->id));
            //добавление элемента <name> в <offer>
            $name = $offer->appendChild($dom->createElement('name'));
            $name->appendChild($dom->createTextNode($v->name));
            //добавление элемента <url> в <offer>
            $url = $offer->appendChild($dom->createElement('url'));
            $url->appendChild($dom->createTextNode('http://freeskidka.ru/discount/' . $v->id ));
            //добавление элемента <description> в <offer>
            $description = $offer->appendChild($dom->createElement('description'));
            $description->appendChild($dom->createTextNode(strip_tags($v->description)));
            //добавление элемента <region> в <offer>
            $region = $offer->appendChild($dom->createElement('region'));
            $region->appendChild($dom->createTextNode('МОСКВА'));
            //добавление элемента <beginsell> в <offer>
            $beginsell = $offer->appendChild($dom->createElement('beginsell'));
            $beginsell->appendChild($dom->createTextNode($v->beginsell));
            //добавление элемента <endsell> в <offer>
            $endsell = $offer->appendChild($dom->createElement('endsell'));
            $endsell->appendChild($dom->createTextNode($v->endsell));
            //добавление элемента <beginvalid> в <offer>
            $beginvalid = $offer->appendChild($dom->createElement('beginvalid'));
            $beginvalid->appendChild($dom->createTextNode($v->beginvalid));
            //добавление элемента <endvalid> в <offer>
            $endvalid = $offer->appendChild($dom->createElement('endvalid'));
            $endvalid->appendChild($dom->createTextNode($v->endvalid));
            //добавление элемента <picture> в <offer>
            $picture = $offer->appendChild($dom->createElement('picture'));
            $urlPic = '';
            if (isset($v->gallery[0]))
                   $urlPic = 'http://freeskidka.ru' . $v->gallery[0]->getHref();
            $picture->appendChild($dom->createTextNode($urlPic));
            //добавление элемента <price> в <offer>
            $price = $offer->appendChild($dom->createElement('price'));
            $price->appendChild($dom->createTextNode($v->price));
            //добавление элемента <discount> в <offer>
            $discount = $offer->appendChild($dom->createElement('discount'));
            $discount->appendChild($dom->createTextNode($v->discount));
            //добавление элемента <discountprice> в <offer>
            $discountprice = $offer->appendChild($dom->createElement('discountprice'));
            $discountprice->appendChild($dom->createTextNode($v->discountprice));
            //добавление элемента <pricecoupon> в <offer>
            $pricecoupon = $offer->appendChild($dom->createElement('pricecoupon'));
            $pricecoupon->appendChild($dom->createTextNode($v->pricecoupon));

                // > добавление элемента <supplier> в <offer>
                $supplier = $offer->appendChild($dom->createElement('supplier'));
                //добавление элемента <name> в <supplier>
                $nameSupplier = $supplier->appendChild($dom->createElement('name'));
                $nameSupplier->appendChild($dom->createTextNode($v->company_name));
                //добавление элемента <url> в <supplier>
                $urlSupplier = $supplier->appendChild($dom->createElement('url'));
                $urlSupplier->appendChild($dom->createTextNode($v->company_url));
                //добавление элемента <tel> в <supplier>
                $telSupplier = $supplier->appendChild($dom->createElement('tel'));
                $telSupplier->appendChild($dom->createTextNode($v->company_tel));
                    // > добавление элемента <addresses> в <supplier>
                    $addresses = $supplier->appendChild($dom->createElement('addresses'));
                        // > добавление элемента <address> в <addresses>
                        $address = $addresses->appendChild($dom->createElement('address'));
                            //добавление элемента <name> в <address>
                            $nameAddress = $address->appendChild($dom->createElement('name'));
                            $nameAddress->appendChild($dom->createTextNode($v->company_address));
                            //добавление элемента <coordinates> в <address>
                            $coordinatesAddress = $address->appendChild($dom->createElement('coordinates'));
                            $coordinatesAddress->appendChild($dom->createTextNode($v->company_coordinates));
                            // КОНЕЦ цикла добавления каждого offer
        }
        $dom->formatOutput = true;
        $dom->save(YiiBase::getPathOfAlias('webroot') . DIRECTORY_SEPARATOR . 'xmlout.xml'); // сохранение в файл
        echo 'файл доступен по адресу /xmlout.xml';

        /*
        $img = ImageHelper::thumb('./testimg', '123.jpg', array(
            'width'  => 310,
            'height' => 100
        ));
        return $img->__toString();
        */
        //через CHtml::image
        //echo CHtml::image('./testimg/123.jpg','alt',array('height'=>'200','width'=>'200'));
        //echo CHtml::image(Yii::app()->request->getBaseUrl() . '/testimg/123.jpg','alt',array('height'=>'200','width'=>'200'));

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
