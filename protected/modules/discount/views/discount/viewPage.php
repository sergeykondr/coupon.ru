<?
$this->page_title = $page->title; //заголовок <h1>
//echo CHtml::encode($page->text);
?>

<h3><? echo CHtml::encode($page->caption); ?></h3>

<div class="row-fluid">
    <div class="span8">
        <div id="myCarousel" class="carousel slide">
            <!-- Carousel items -->
            <div class="carousel-inner">


                <?
                $i=0; //счетчик для первой картинки
                foreach($page->gallery as $gal)
                {
                    if ($i==0)
                    {
                    $i++;
                ?>
                    <div class="item active">
                    <?
                        echo CHtml::image($gal->getHref());
                    ?>
                    </div>

                    <?
                    }
                    else
                    {
                    ?>
                        <div class="item">
                        <?
                        echo CHtml::image($gal->getHref());
                        ?>
                        </div>
                    <?
                    }
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
                    <? echo CHtml::encode($page->text); ?>
                    <p>Клуб MAC – один из старейших парапланерных клубов, который стоял у истоков зарождения параглайдинга в Москве. Основатель клуба и его бессменный руководитель на протяжении уже более 10 лет – Сергей Елизаров, пилот с 18-летним опытом полетов и более чем 800 часами налета. Грамотная и квалифицированная команда клуба в сегодняшнем составе работает уже седьмой год и постоянно повышает свой летный и инструкторский опыт, выезжая на учебно-тренировочные сборы в различные страны.</p>
                    <b>Условия</b>
                    <ul>
                        <li>Купон №1 дает право на полет на параплане для одного в клубе Macpara (1590 рублей вместо 3200). Скидка 53%!</li>
                        <li>Купон №2 дает право на полет на параплане для двоих в клубе Macpara (2990 рублей вместо 6400). Скидка 53%!</li>
                        <li>Купон №3 дает право на полет на параплане для двоих с фото/видеосъемкой в клубе Macpara (3290 рублей вместо 7400). Скидка 55%!</li>
                        <li>В стоимость купона входит теоретическая и наземная подготовка к полёту.</li>
                        <li>Продолжительность курса подготовки к полету —10-15 мин.</li>
                        <li>Продолжительность полета — от 5 до 6 минут.</li>
                        <li>С собой необходимо иметь паспорт, купон, удобную обувь (желательно кроссовки на твердой подошве) и удобную одежду.</li>
                        <li>Полеты могут быть отложены на другое время или перенесены на другую дату, если метеоусловия и другие факторы препятствуют выполнению полетов.</li>
                        <li>Ограничение по весу: не более 120 кг.</li>
                        <li>Купоном возможно воспользоваться с 21 сентября до 23 октября 2012 года.</li>
                        <li>Один человек или пара (от 18 лет) могут использовать только один купон по данной акции и только один раз.</li>
                        <li>Лица младше 18 лет допускаются до полетов только с письменного разрешения родителей.</li>
                        <li>Вы можете приобрести неограниченное количество купонов в подарок (в расчете один купон в подарок одному человеку или паре).</li>
                        <li>Необходима предварительная запись по телефону:  +7 (926) 524-8448 с оповещением о наличии купона на скидку.</li>
                        <li>Скидка не суммируется с другими действующими предложениями клуба.</li>
                        <li>Противопоказания: состояние алкогольного или наркотического опьянения.</li>
                        <li>Обязательно предьявляйте распечатанный купон.</li>
                    </ul>
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



