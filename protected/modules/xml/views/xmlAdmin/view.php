<?
$this->page_title = t('Просмотр XML Дискаунта');




$this->widget('AdminDetailView', array(
    'data'=>$model,
    'attributes'=>array(
        'category_id',
        'name',
        'description',
        'xml_imp_id',
        array(
            'name'  => 'beginsell (Дата начала продаж купонов. Формат по русски)',
            'value' => date('d.m.Y H:i:s', strtotime($model->beginsell))
        ),
        array(
            'name'  => 'endsell (Дата окончания продаж купонов. Формат по русски)',
            'value' => date('d.m.Y H:i:s', strtotime($model->endsell))
        ),
        array(
            'name'  => 'beginvalid (Дата начала действия купона. Формат по русски)',
            'value' => date('d.m.Y H:i:s', strtotime($model->beginvalid))
        ),
        array(
            'name'  => 'endvalid (Дата окончания действия купона. Формат по русски)',
            'value' => date('d.m.Y H:i:s', strtotime($model->endvalid))
        ),
        array(
            'name'  => 'text',
            'type'  => 'raw',
            'value' => $model->text
        ),
    ),
));
