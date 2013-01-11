<?= (($index % 3)==0 ) ? '<div class="row-fluid">' : '' ?>
    <div class="span4 well">
        <?
        $i=0; //счетчик для первой картинки
        $imghtml=''; //сюда пишем адрес первой картинки

        //если акция наша - отображаем первую картинку из галереи
        //если импортированная - то ссылается на сайт
        if ($data->our)
        {
            foreach($data->gallery as $gal)
            {
                $imghtml = CHtml::image($gal->getHref(),'',array('class'=>'img-rounded'));
                break; //т.к. берем первую картинку
            }
        }
        else
        {

            $imghtml = CHtml::image($data->xml[0]->getHref(),'',array('class'=>'img-rounded'));
        }

        echo CHtml::link($imghtml, $data->href, array('class' => 'page-title'));
        ?>
        <br>
        <!--заголовок-->
        <?= CHtml::link(CHtml::encode($data->name), '/discount/'.$data->id, array('class' => 'page-title')); ?>
        <br>
        <p><small>Купон действует до <?= Yii::app()->dateFormatter->format('d MMMM yyyy', $data->endvalid); ?></small></p>
        Метро
        <br>
        Скидка <? echo CHtml::encode($data->discount); ?>% за <? echo CHtml::encode($data->pricecoupon); ?> р.
        <br>
        <div class="row-fluid">
            <div class="span6">
                <center>Осталось:  <?=$data->expires('short');?></center>
            </div>
            <div class="span6">
                <center>Купили:  <? echo $data->cheat() + $data->numbers_buy;  ?></center>
                <!-- <br clear="all"/> -->
            </div>
        </div>
    </div>
<?= (($index % 3)==2 ) ? '</div>' : '' ?>

<!--
<div class="span4">
    <h2>Heading</h2>
    <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
    <p><a class="btn" href="#">View details »</a></p>
</div>
-->