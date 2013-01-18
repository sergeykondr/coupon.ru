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

        'beginsell',
        'xml_imp_id',
        array(
            'class'=>'CButtonColumn',
        ),
    ),
));
?>




