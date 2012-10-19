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
        'name',
        'description',
        array(
            'name'  => 'beginsell (Дата начала продаж купонов)',
            'value' => date('d.m.Y h:i', strtotime($model->beginsell))
        ),
        array(
            'name'  => 'endsell (Дата окончания продаж купонов)',
            'value' => date('d.m.Y h:i', strtotime($model->endsell))
        ),
        array(
            'name'  => 'beginvalid (Дата начала действия купона)',
            'value' => date('d.m.Y h:i', strtotime($model->beginvalid))
        ),
        array(
            'name'  => 'endvalid (Дата окончания действия купона)',
            'value' => date('d.m.Y h:i', strtotime($model->endvalid))
        ),
        array(
            'name'  => 'text',
            'type'  => 'raw',
            'value' => $model->text
        ),
    ),
));
