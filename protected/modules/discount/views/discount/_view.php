<?= (($index % 3)==0 ) ? '<div class="row-fluid">' : '' ?>
    <div class="span4 well">
        <div class="row-fluid">
            <div class="img_category">
            <?
                $imghtml = CHtml::image(Yii::app()->request->getBaseUrl(true) . $data->urlImageCropShow(),'',array('class'=>'img-rounded'));
                echo CHtml::link($imghtml, $data->href, array('class' => 'page-title'));
            ?>
            </div>
            <br>
            <div class="body_category">
                <?= CHtml::link(CHtml::encode($data->name), '/discount/'.$data->id, array('class' => 'page-title')); ?>
            </div>
        </div>
        <div class="row-fluid">
            <div class="zalivka">
            Скидка <? echo CHtml::encode($data->discount); ?>% за <? echo CHtml::encode($data->pricecoupon); ?> р.
            </div>
        </div>
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
        <hr style="margin: 0px 0;">
        <div class="row-fluid">
            <div style="text-align: center; padding: 5px 0px 5px 0px ;">
                <small>Купон действует до <?= Yii::app()->dateFormatter->format('d MMMM yyyy', $data->endvalid); ?></small>
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