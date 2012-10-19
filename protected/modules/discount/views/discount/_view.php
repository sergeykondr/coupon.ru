<br>
Заголовок: <?= CHtml::link(CHtml::encode($data->name), $data->href, array('class' => 'page-title')); ?>
<br>
Фото:
<br>

Истекает: <?= Yii::app()->dateFormatter->formatDateTime($data->endsell, 'long', 'short') ?>
<br>

<?
//$this->now = new CDbExpression('NOW()');
echo $a =  time();
echo '<br>';
echo $b = strtotime($data->endsell);
echo '<br>';
echo $c = $b-$a;
echo '<br>осталось часов: ' . floor($c/60/60);

?>
<br>
<?= Yii::app()->dateFormatter->format('d MMMM yyyy hh mm', time()); ?>
<br>
Осталось:

<br>
Купили
<br>
Метро
<br>
Скидка <? echo CHtml::encode($data->discount); ?>% за <? echo CHtml::encode($data->pricecoupon); ?> р.
<br clear="all"/>
<!--
<div class="span4">
    <h2>Heading</h2>
    <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
    <p><a class="btn" href="#">View details »</a></p>
</div>
-->