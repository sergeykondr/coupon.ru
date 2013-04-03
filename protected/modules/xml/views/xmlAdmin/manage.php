<?

$this->widget('AdminGridView', array(
    'id'           => 'page-grid',
    'dataProvider' => $model->search('xml'),
    'filter'       => $model,
    'columns' => array(
        array(
            'name' => 'name',
            'type' => 'raw'
        ),
        'endsell',
        [
            'name'   => 'category_id',
            'filter' => CHtml::listData(Category::model()->findAll(), 'id', 'name'),
            'value'  => '$data->category_id_value'
        ],
        'xml_imp_id',
        'xml_imp_url',
        array(
            'class'=>'CButtonColumn',
        ),
    ),
));
?>




