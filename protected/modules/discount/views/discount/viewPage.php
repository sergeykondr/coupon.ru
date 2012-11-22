<?
$this->page_title = $page->name; //заголовок <h1>
//echo CHtml::encode($page->text);
?>

<h4><? echo CHtml::encode($page->description); ?></h4>

<?
$list = CHtml::listData($metro, 'id', 'name');

    $this->widget('application.components.formElements.chosen.Chosen',array(
    'name' => 'metro', // input name
    'value' => array(1, 55), // selection
    'multiple'=>true,
    'data' => $list,
    ));


echo CHtml::dropDownList('listname', 'F',
    array('M' => 'Male', 'F' => 'Female'));
?>
<?php echo CHtml::dropDownList('categories', '',
    $list,
    array('empty' => '(Select a category)'));
?>

<div class="row-fluid">
    <div class="span8">
        <div id="myCarousel" class="carousel slide carousel-hidden">
            <!-- Carousel items -->
            <div class="carousel-inner">


            <?
                $i=0; //счетчик для первой картинки
                foreach($page->gallery as $gal)
                {
                    ?>
                    <div class="item<?=(!$i++) ? ' active">' : '">';
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
        <p>Скидка до <?=$page->discount;?> % за <?=$page->pricecoupon;?> Р.</p>

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
                    'action' => array('/discount/buy/'.$page->id),
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

        <?php $this->widget('bootstrap.widgets.BootButton', array(
          'label'=>'Купить',
           'url'=>'#buyModal',
            'type'=>'primary',
            'htmlOptions'=>array('data-toggle'=>'modal'),
        )); ?>




        <br>
        Уже купили: <? echo $page->cheat() + $page->numbers_buy;  ?><br>
        Купон действует до: <?= Yii::app()->dateFormatter->format('d MMMM yyyy', $page->endvalid); ?><br>
        До завершения осталось: <?=$page->expires('long');?> <br>

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
                    ?>
                </div>
                <div class="tab-pane fade" id="second">

                    <div id="map" style="width:662px;height:400px"></div>

                    <p><?= CHtml::encode($page->company_name); ?></p>
                    <p><?= CHtml::link(CHtml::encode($page->company_url), $page->company_url); ?></p>
                    <p>тел.: <?= CHtml::encode($page->company_tel); ?></p>
                    <p><?= CHtml::encode($page->company_address); ?></p>
                    <p><?= CHtml::encode($page->company_time); ?></p>
                    <p>Метро: <?php echo implode(', ', CHtml::listData($page->metros, 'id', 'name')); ?></p>
                </div>
                <div class="tab-pane fade" id="third">
                    <p>Комментарии</p>
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