


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