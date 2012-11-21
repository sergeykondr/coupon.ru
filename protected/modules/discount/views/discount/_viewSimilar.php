<?
    $i=0; //счетчик для первой картинки
    $imghtml=''; //сюда пишем адрес первой картинки
    foreach($data->gallery as $gal)
    {
        $imghtml = CHtml::image($gal->getHref(),'',array('class'=>'img-rounded'));
        break;
    }
    echo CHtml::link($imghtml, $data->href, array('class' => 'page-title'));
?>
<?php echo CHtml::link(CHtml::encode($data->name), array($data->id)); ?>
<br />
<hr>
