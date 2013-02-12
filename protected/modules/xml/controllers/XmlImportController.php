<?
class XmlImportController extends Controller
{
    public $fileXmlLoad; //загружаемый xml файл
    public $urlsImage = array(); //массив url картинок
    public $urlsImageContent; //массив url и контента картинок
    //public $cityRadar = 'http://fun2mass.ru/kuponator.xml';
    //public $cityRadar = 'http://cityradar.ru/kupongid.xml';


    public static function actionsTitles()
    {
        return array(
            'cityradarImport'         => 'Импорт дискаунтов Cityradar',
            'fan2massImport'          => 'Импорт дискаунтов Fan2mass',

            /*
            'index'        => 'Все акции',
            'views'         => 'Просмотр акции',
            'category' => 'Просмотр категорий',
            'buy' => 'Покупка акции',
            */
        );
    }


    //получение url и контента картинок для несуществющих дискаунтов
    private function loadHrefImage()
    {
        if (empty($this->fileXmlLoad))
            $this->xmlLoad();
        foreach ($this->fileXmlLoad->offers->offer as $offer)
        {
            //только купоны для Москвы
            if ($offer->region != "Москва")
                continue; // или удалить этот элемент
            //если id дискаунта такой уже есть, то пропускаем
            if (Discount::model()->findByAttributes(array('xml_imp_id'=>$offer->id)))
                continue;
            //url картинок
            $this->urlsImage[] = (string)$offer->picture;
        }
    }


    private function LoadContentImage()
    {
        //получение контента картинок
        $this->urlsImageContent = CurlHelper::multi($this->urlsImage);
    }



    public function actionCityradarImport()
    {
        $this->ImportXml('http://cityradar.ru/kupongid.xml');
    }


    public function actionFan2massImport()
    {
        $this->ImportXml('http://fun2mass.ru/kuponator.xml');
    }

    private function ImportXml($url)
    {
        $this->fileXmlLoad = simplexml_load_file($url);

        //загружаем картинки
        $this->loadHrefImage();
        $i = 0;
        $discountSave = array();
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
            $model->beginsell = date('Y-m-d H:i:s', strtotime($offer->beginsell));
            $model->endsell = date('Y-m-d H:i:s', strtotime($offer->endsell));
            $model->beginvalid = date('Y-m-d H:i:s', strtotime($offer->beginvalid));
            $model->endvalid = date('Y-m-d H:i:s', strtotime($offer->endvalid));
            $model->xml_imp_picture = (string)$offer->picture; //лишнее?
            $this->urlsImage[] = (string)$offer->picture;
            $model->price = $offer->price;
            $model->discount = $offer->discount;
            $model->discountprice = $offer->discountprice;
            $model->pricecoupon = $offer->pricecoupon;
            $model->cheat = rand(100, 3000);
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
            if ($model->save())
            {
                $i++ ;
                $discountSave[$i]['discountid'] = $model->id;
                $discountSave[$i]['urlimage'] = (string)$offer->picture;
                // указываем ближайшие метро. функционал в разработке
                //3) записываем SEO теги: $model->id; $offer->name; $offer->supplier->name;
                $this->addMetaTegImport($model->id, $offer->name, $offer->supplier->name);
            }
        }

        $this->addPicturesImport($discountSave);
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
    private function addPicturesImport($discountSave)
    {
        //разбить. грузить кратинки вместе. сохранять
        $discountChunk = array_chunk($discountSave, 150);

        foreach ($discountChunk as $k => $v)
        {
            $urlsImages = array();
            //собираем в единый массив url картинок
            foreach ($v as $coupon)
            {
                $urlsImages[] = $coupon['urlimage'];
                //$coupon->urlimage;
            }

            //грузим весь массив картинок
            //unset($this->urlsImageContent); //уничтожаем переменную для очистки памяти
            $this->urlsImageContent = array();
            $this->urlsImageContent = CurlHelper::multi($urlsImages);


            //сохраняем каждую картинку поочередно и заполняем модель MediaFile
            foreach ($v as $coupon)
            {
                $arrayUrlImage = explode("/", $coupon['urlimage']);
                $imgNameOriginal = end($arrayUrlImage);
                $imgName = FileSystemHelper::vaultResolveCollision(Discount::PATH_XML_IMG, $imgNameOriginal); //определяем уникальное имя для указанной папки
                file_put_contents(YiiBase::getPathOfAlias('webroot') . '/'. Discount::PATH_XML_IMG . '/' . $imgName , $this->urlsImageContent[$coupon['urlimage']]); //записываем в текущ. папку

                list($path, $imgNamenew) = FileSystemHelper::moveToVault(Discount::PATH_XML_IMG . '/' . $imgName, Discount::PATH_XML_IMG, true);
                unset($this->urlsImageContent[$coupon['urlimage']]); // удаляем элемент с картинкой

                // записываем информацию о картинке в MediaFile
                $media = new MediaFile();
                $media->object_id = $coupon['discountid'];
                $media->model_id = 'Discount';
                $media->name = $imgNamenew;
                $media->title = $imgNameOriginal;
                $media->tag = 'xml';
                $media->order = 1;
                $media->path = $path;
                $media->types = 'img';
                $media->save();
            }
        }
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

}