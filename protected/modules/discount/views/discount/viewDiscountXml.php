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
                <?
                $i=0; //счетчик для первой картинки
                foreach($page->xml as $gal)
                {
                    ?>
                        <div class="item<?=(!$i++) ? ' active' : ''; echo '">';
                    /*
                    if ($i==0)
                    {
                        echo ' active';
                       $i++;
                    }
                    echo '">';
                    */
                    echo CHtml::image($gal->getHref());
                    echo '</div>';

                }
                ?>
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
        Купили: <? echo $page->cheat() + $page->numbers_buy;  ?> <i class="icon-tags"></i><br>
        Купон действует до: <?= Yii::app()->dateFormatter->format('d MMMM yyyy', $page->endvalid); ?><br>
    </div>
    <div class="span4 well blueborder" style="font-size: 14px; text-align: center; font-weight: bold;">
        <? if ($page->isActual())
        {?>
            <!-- Начало описания виджета модального окна -->
            <?php $this->beginWidget('bootstrap.widgets.BootModal', array('id'=>'buyModal')); ?>
            <?
            $qForm = new Buy;
            $form = $this->beginWidget('CActiveForm', array(
                'id' => 'EmailForm',
                'enableClientValidation' => true,
                'clientOptions' => array(
                    'validateOnSubmit' => true,
                ),
                'action' => array('/discount/xmlbuy/'.$page->id),
            ));
            ?>
            <div class="modal-header">
                <a class="close" data-dismiss="modal">&times;</a>
                <h3>Приобретение купона</h3>
            </div>
            <div class="modal-body">
                <?php echo $form->errorSummary($qForm); ?>
                <?php echo $form->labelEx($qForm,'email'); ?>
                <?php echo $form->textField($qForm,'email', array('size'=>35)); ?>
                <?php echo $form->error($qForm,'email'); ?>
                <?//php echo $form->textFieldRow($model, 'textField', array('class'=>'span3')); ?>
                <?php // echo CHtml::submitButton('Отправить'); ?>
            </div>

            <div class="modal-footer">
                <?php $this->widget('bootstrap.widgets.BootButton', array('buttonType'=>'submit', 'label'=>'Получить купон')); ?>
            </div>
            <?php $this->endWidget(); ?>
            <?php $this->endWidget(); ?>
            <!-- Конец описания виджета модального окна -->
            Скидка до <?=$page->discount;?> % за <?=$page->pricecoupon;?> р.&nbsp;&nbsp;&nbsp;<?php $this->widget('bootstrap.widgets.BootButton', array(
            'label'=>'Купить',
            'url'=>'#buyModal',
            'type'=>'primary',
            'htmlOptions'=>array('data-toggle'=>'modal'),
        )); ?>
            <br>
            <?} //конец блока if?>

    </div>
    <div class="span4 well" >
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
            center: [<? echo CHtml::encode($page->ycoord) . ',' . CHtml::encode($page->xcoord) ; ?>],
            zoom: 13
        }),
            // При создании метки указываем ее свойства:  текст для отображения в иконке и содержимое балуна,
            // который откроется при нажатии на эту метку
            myPlacemark = new ymaps.Placemark([<? echo CHtml::encode($page->ycoord) . ',' . CHtml::encode($page->xcoord) ; ?>], {
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
        myMap.controls.add('zoomControl');
        myMap.controls.add('scaleLine');
        myMap.controls.add('mapTools');

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
                    echo  $page->description;
                    ?>
                </div>
                <div class="tab-pane fade" id="second">

                    <? if (!CHtml::encode($page->xcoord)=='')
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
                <div class="tab-pane fade" id="third" style="overflow:hidden">
                    <!-- Put this script tag to the <head> of your page -->
                    <script type="text/javascript" src="//vk.com/js/api/openapi.js?76"></script>

                    <script type="text/javascript">
                        VK.init({apiId: 3376228, onlyWidgets: true});
                    </script>

                    <!-- Put this div tag to the place, where the Comments block will be -->
                    <div id="vk_comments"></div>
                    <script type="text/javascript">
                        VK.Widgets.Comments("vk_comments", {limit: 10, width: "660", attach: false});
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