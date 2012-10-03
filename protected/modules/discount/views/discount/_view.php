<br>
Заголовок: <?= CHtml::link(CHtml::encode($data->title), $data->href, array('class' => 'page-title')); ?>
<br>
Описание: <?= CHtml::link(CHtml::encode($data->caption), $data->href, array('class' => 'page-title')); ?>
<br>
Фото:
<br>
Истекает: <?= Yii::app()->dateFormatter->formatDateTime($data->date, 'long', 'short') ?>
<br>
Осталось:
<br clear="all"/>

<!--
<div class="span4">
    <h2>Heading</h2>
    <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
    <p><a class="btn" href="#">View details »</a></p>
</div>
-->