<?
    /*
    $i=0; //счетчик для первой картинки
    $imghtml=''; //сюда пишем адрес первой картинки
    $place = ($data->our) ? 'gallery' : 'xml';
    foreach($data->$place as $gal)
    {
        $imghtml = CHtml::image($gal->getHref(),'',array('class'=>'img-rounded'));
        break;
    }
    echo CHtml::link($imghtml, '/discount/' . $data->id, array('class' => 'page-title'));
    */
    echo CHtml::link(CHtml::encode($data->name), $this->createUrl('/discount/discount/view',array('id'=>$data->id)));
?>
<br />
<hr>
