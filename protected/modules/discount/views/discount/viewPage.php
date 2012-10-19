<?
$this->page_title = $page->title; //заголовок <h1>
//echo CHtml::encode($page->text);
?>

<h3><? echo CHtml::encode($page->title); ?></h3>

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
                    if ($i==0)
                    {
                        echo ' active';
                       $i++;
                    }
                    echo '">';
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
        <p>vk.com fb.com g+</p>
    </div>
</div>

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
                    <? echo CHtml::encode($page->text);
                    $parts = explode('{{cut}}', $page->text);
                    echo array_shift($parts);
                    ?>
                </div>
                <div class="tab-pane fade" id="second">
                    <p>Парапланерный клуб MAC Para</p>
                    <p>http://www.macpara.ru</p>
                    <p>+7 (926) 777-7136</p>
                    <p>Круглосуточно</p>
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



