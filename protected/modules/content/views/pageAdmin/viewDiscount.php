<?
$this->page_title = t('Просмотр Акции');

$this->tabs = array(
    'редактировать'  => $this->createUrl('update', array('id' => $model->id)),
    'список страниц' => $this->createUrl('manage')
);


$this->widget('AdminDetailView', array(
    'data'=>$model,
    'attributes'=>array(
        'category_id',
        'title',
        'caption',
        'numbers_buy',
        'price',
        array(
            'name'  => 'date',
            'value' => date('d.m.Y h:i', strtotime($model->date))
        ),
        array(
            'name'  => 'text',
            'type'  => 'raw',
            'value' => $model->text
        ),
    ),
));
