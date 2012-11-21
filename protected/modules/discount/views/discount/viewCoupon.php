<html>
<head>
    <title>Электронный купон. <? echo CHtml::encode($page->name); ?></title>



<script src="http://api-maps.yandex.ru/2.0-stable/?load=package.full&lang=ru-RU" type="text/javascript"></script>
<script type="text/javascript">
    // Как только будет загружен API и готов DOM, выполняем инициализацию
    var myMap;
    ymaps.ready(init);


    function init () {
        myMap = new ymaps.Map("map", {
            center: [55.76, 37.64],
            zoom: 11
        }),
            // При создании метки указываем ее свойства:  текст для отображения в иконке и содержимое балуна,
            // который откроется при нажатии на эту метку
            myPlacemark = new ymaps.Placemark([55.7, 37.6], {
                // Свойства
                balloonContent: '<?= CHtml::encode($page->company_address); ?>'
                //iconContent: 'Щелкни по мне',
                //balloonContentHeader: 'Заголовок',
                //balloonContentBody: 'Содержимое <em>балуна</em>',
                //balloonContentFooter: 'Подвал'
            }, {
                // Опции
                preset: 'twirl#blueStretchyIcon' // иконка растягивается под контент
            });

        // Добавляем метку на карту
        myMap.geoObjects.add(myPlacemark);

    }
</script>
</head>
<body>
    <h2>Электронный купон</h2>
    <h3>Данный купон необходимо предъявить на месте для получения скидки или услуги</h3>
    <h4><? echo CHtml::encode($page->name); ?></h4>
    <h4><? echo CHtml::encode($page->description); ?></h4>
    <?//id покупки из buy (в 3ичной системе исчисления) - категория дискаунта - колво реально купивших купон?>
    <p>Код купона: <? echo base_convert("$buyCurrentId",10,3).'-'.$page->category_id.'-'.$page->numbers_buy ?></p>


    <p>Купон действует до: <?= Yii::app()->dateFormatter->format('d MMMM yyyy', $page->endvalid); ?></p>
    <p>Телефон: <?= CHtml::encode($page->company_tel); ?></p>
    <p>Адрес: <?= CHtml::encode($page->company_address); ?></p>
    <p>Карта</p>
    <div id="map" style="width:662px;height:400px"></div>



    <?
    $this->page_title = $page->name; //заголовок <h1>
    //echo CHtml::encode($page->text);
    ?>

    <h4><? echo CHtml::encode($page->description); ?></h4>
</body>
</html>