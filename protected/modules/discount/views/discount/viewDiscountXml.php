<?
$this->page_title = $page->name; //заголовок <h1>
//echo CHtml::encode($page->text);
?>

<h4><? echo CHtml::encode($page->short_desc); ?></h4>

<div class="row-fluid">
    <div class="span8">
        <div id="myCarousel" class="carousel slide carousel-hidden">
            <!-- Carousel items -->
            <div class="carousel-inner">




                <div class="item active">
                    <img src="<?=$page->xml[0]->getHref();?>" alt="">
                </div>
                <!--
                <div class="item">
                    <img src="http://alkupone.ru/system/picture/7615/sport_paragliding_harness.jpg" alt="">
                </div>
                <div class="item">
                    <img src="http://alkupone.ru/system/picture/7616/huge_1346429445.jpg" alt="">
                </div>
                -->
            </div>
            <!-- Carousel nav
            <a class="carousel-control left" href="#myCarousel" data-slide="prev">‹</a>
            <a class="carousel-control right" href="#myCarousel" data-slide="next">›</a>
            -->
        </div>
    </div>
    <div class="span4 well">
        <p>Скидка до <?=$page->discount;?> % за <?=$page->pricecoupon;?> Р.</p>

        <? if ($page->isActual()) //если акция актуальна, то выводим кнопку 'купить' с модальным окном
        { ?>


        <a href="<?=$page->xml_imp_url;?>">Купить</a>
        <br>

        <?} //конец блока if?>



       <!-- Купили (нужно ли?): <? //echo $page->cheat() + $page->numbers_buy;  ?><br> -->
        Купон действует до: <?= Yii::app()->dateFormatter->format('d MMMM yyyy', $page->endvalid); ?><br>
        <? if ($page->isActual())
        {
            ?>До завершения осталось: <?=$page->expires('long');?> <br>
        <?
        }
        else
        {
            ?><b>Акция завершена</b><?
        }
        ?>


    </div>
    <div class="span4 well">
        Поделиться в социальных сетях:<br>
       <script type="text/javascript" src="//yandex.st/share/share.js" charset="utf-8"></script>
       <div class="yashare-auto-init" data-yashareL10n="ru" data-yashareType="none" data-yashareQuickServices="yaru,vkontakte,facebook,twitter,odnoklassniki,moimir,lj"></div>
    </div>
</div>

<script src="http://api-maps.yandex.ru/2.0-stable/?load=package.full&lang=ru-RU&coordorder=longlat" type="text/javascript"></script>
<script type="text/javascript">
    // Как только будет загружен API и готов DOM, выполняем инициализацию
    var myMap;
    ymaps.ready(init);

    function init () {
        myMap = new ymaps.Map("map", {
            center: [37.631534,55.763964],
            zoom: 10
        }),
            // При создании метки указываем ее свойства:  текст для отображения в иконке и содержимое балуна,
            // который откроется при нажатии на эту метку
            myPlacemark = new ymaps.Placemark([<?= CHtml::encode($page->company_coordinates); ?>], {
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

<div class="row-fluid">
    <div class="span8">
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
                    echo $page->text;

                    //перед <ul><li>
                    echo '<ul><li>';
                    $descript = $page->description;
                    $countLi = substr_count($descript, ';') - 1;
                    $descript = str_replace(';', ';</li><li>', $descript, $countLi);
                    echo $descript;
                    echo '</li></ul>';


                    ?>
                </div>
                <div class="tab-pane fade" id="second">

                    <? if (!CHtml::encode($page->company_coordinates)=='')
                        echo '<div id="map" style="width:662px;height:400px"></div>';
                    ?>


                    <p><?= CHtml::encode($page->company_name); ?></p>
                    <p><?= CHtml::link(CHtml::encode($page->company_url), $page->company_url); ?></p>
                    <p>тел.: <?= CHtml::encode($page->company_tel); ?></p>
                    <p><? // echo CHtml::encode($page->company_address); ?>
                        <?
                        $pos = strpos($page->company_address, '||');
                        if ($pos === false)
                        {
                            //не найдено. т.е. один адрес
                            echo CHtml::encode($page->company_address);
                        }
                        else
                        {
                            //адресов много
                            $parts = explode('||', $page->company_address);
                            foreach ($parts as $k=>$v)
                            {
                                echo CHtml::encode($v) . '; ';
                            }
                        }
                        ?>
                    </p>

                    <p><?= CHtml::encode($page->company_time); ?></p>

                    <? if  (CHtml::listData($page->metros, 'id', 'name'))
                        echo '<p>Метро: ' . implode(', ', CHtml::listData($page->metros, 'id', 'name')) . '</p>'; ;
                    ?>
                    <!-- <p>Метро: <?php // echo implode(', ', CHtml::listData($page->metros, 'id', 'name')); ?></p> -->
                </div>
                <div class="tab-pane fade" id="third">
                    <p>Комментарии</p>
                    <!-- Put this script tag to the <head> of your page -->
                    <script type="text/javascript" src="//vk.com/js/api/openapi.js?76"></script>

                    <script type="text/javascript">
                        VK.init({apiId: 3376228, onlyWidgets: true});
                    </script>

                    <!-- Put this div tag to the place, where the Comments block will be -->
                    <div id="vk_comments"></div>
                    <script type="text/javascript">
                        VK.Widgets.Comments("vk_comments", {limit: 10, width: "496", attach: false});
                    </script>
                </div>
            </div>
        </div>
    </div>

    <div class="span4 well">
        <b>Похожие акции:</b>
        <?php $this->widget('zii.widgets.CListView', array(
        'dataProvider'=>$similars,
        'itemView'=>'_viewSimilar',
        'summaryText'  => '',
          ));
        ?>
    </div>
</div>

<script type="text/javascript">
    $('a[data-toggle="tab"]').on('shown', function (e) {
        ymaps.ready(function () {
            //alert($('#profile').is(':visible'));
            console.log(e.target);
            var strUrl = String(e.target);
            strUrl.indexOf("profile");
            //alert ($(e.target).attr('id'));

            if ($(e.target).attr('href') == '#second' )
            {
                myMap.container.fitToViewport();
            }
        })
    })
</script>