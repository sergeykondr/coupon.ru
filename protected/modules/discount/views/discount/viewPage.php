<?
$this->page_title = $page->name; //заголовок <h1>
//echo CHtml::encode($page->text);
?>

<h3><? echo CHtml::encode($page->description); ?></h3>

<div class="row-fluid">
    <div class="span8">
        <div id="myCarousel" class="carousel slide">
            <!-- Carousel items -->
            <div class="carousel-inner">


            <?
                $i=0; //счетчик для первой картинки
                foreach($page->gallery as $gal)
                {
                    ?>
                    <div class="item<?
                    /*
                    if ($i==0)
                    {
                        echo ' active';
                       $i++;
                    }
                    echo '">';
                    */
                    echo $action = (!$i++) ? ' active">' : '">';

                    echo CHtml::image($gal->getHref());
                    echo '</div>';
                }
            ?>


                <!--
                <div class="item active">
                    <img src="http://alkupone.ru/system/picture/7616/huge_1346429445.jpg" alt="">
                </div>
                <div class="item">
                    <img src="http://alkupone.ru/system/picture/7615/sport_paragliding_harness.jpg" alt="">
                </div>
                <div class="item">
                    <img src="http://alkupone.ru/system/picture/7616/huge_1346429445.jpg" alt="">
                </div>
                -->
            </div>
            <!-- Carousel nav -->
            <a class="carousel-control left" href="#myCarousel" data-slide="prev">‹</a>
            <a class="carousel-control right" href="#myCarousel" data-slide="next">›</a>
        </div>
    </div>
    <div class="span4 well">
        <p>Скидка до NNN % за MMM Р.   Купить</p>
        Уже купили
        Купон действует до
        До завершения осталось:
        <p>vk.com fb.com g+</p>
    </div>
</div>
<script type="text/javascript">
    // Создает обработчик события window.onLoad
    YMaps.jQuery(function () {
        // Создает экземпляр карты и привязывает его к созданному контейнеру
        var map = new YMaps.Map(YMaps.jQuery("#YMapsID")[0]);

        // Устанавливает начальные параметры отображения карты: центр карты и коэффициент масштабирования
        map.setCenter(new YMaps.GeoPoint(30.343561,60.050282), 14);


        //map.addControl(new YMaps.TypeControl());
        map.addControl(new YMaps.ToolBar());
        map.addControl(new YMaps.Zoom());
        //map.addControl(new YMaps.MiniMap());
        //map.addControl(new YMaps.ScaleLine());

        //включить масштабирование колесиком мыши
        map.enableScrollZoom();

        // Создает метку и добавляет ее на карту
        // Создает метку с маленьким значком красного цвета
        var placemark = new YMaps.Placemark(new YMaps.GeoPoint(30.343561, 60.050282),{style: "default#darkblueSmallPoint"});



        placemark.name = "Пивной ресторан Флинт";
        placemark.description = "проспект Просвещения д.33";
        map.addOverlay(placemark);

        // Открывает балун
        //placemark.openBalloon();




        // Создает метку с маленьким значком красного цвета



    })
    enableScrollZoom();


</script>
<div class="row-fluid">
    <div class="span8">
        табы
        <div class="bs-docs-example">
            <ul id="myTab" class="nav nav-tabs">
                <li class="active"><a href="#first" data-toggle="tab">Описание/Условие</a></li>
                <li class=""><a href="#second" data-toggle="tab">Контакты</a></li>
                <li class=""><a href="#third" data-toggle="tab">Комментарии</a></li>
            </ul>
            <div id="myTabContent" class="tab-content">
                <div class="tab-pane fade active in" id="first">
                    <?
                    //echo CHtml::encode($page->text);
                    //$parts = explode('{{cut}}', $page->text);
                    //echo array_shift($parts);
                    echo CHtml::encode($page->text);
                    ?>
                </div>
                <div class="tab-pane fade" id="second">
                    <script src="http://api-maps.yandex.ru/1.1/index.xml?key=AFf-glABAAAAqNw4bwQA9awjTssGwVzj3NjVQYoih034tyQAAAAAAAAAAADfnVtM3sJaBJBFRPGFg7xkVUJXUA==" type="text/javascript"></script>


                    <div id="YMapsID" style="width:600px;height:400px"></div>
                    <p><?= CHtml::encode($page->company_name); ?></p>
                    <p><?= CHtml::link(CHtml::encode($page->company_url), $page->company_url); ?></p>
                    <p>тел.: <?= CHtml::encode($page->company_tel); ?></p>
                    <p><?= CHtml::encode($page->company_address); ?></p>
                    <p><?= CHtml::encode($page->company_time); ?></p>

                </div>
                <div class="tab-pane fade" id="third">
                    <p>Комментарии</p>
                </div>

            </div>
        </div>
    </div>
    <div class="span4 well">
        блоки похожих акций
    </div>
</div>


<script type="text/javascript">
    YMaps.jQuery("#second").bind('click', function () {
        $('#YMapsID').toggle();
        map.redraw(); // Перерисовка карты
        return false;
    });
</script>



