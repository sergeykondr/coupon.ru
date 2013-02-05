#!/usr/local/bin/php
<?
class XmlImportController extends Controller
{
    public $fileXmlLoad; //загружаемый xml файл
    public $urlsImage = array(); //массив url картинок
    public $urlsImageContent; //массив url и контента картинок
    public $cityRadar = 'http://cityradar.ru/kupongid.xml';


    public static function actionsTitles()
    {
        return array(
            'import'         => 'Импрт дискаунтов',
            /*
            'index'        => 'Все акции',
            'views'         => 'Просмотр акции',
            'category' => 'Просмотр категорий',
            'buy' => 'Покупка акции',
            */
        );
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

}