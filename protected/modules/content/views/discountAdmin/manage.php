<?
$this->tabs = array(
    'Добавить дискаунт' => $this->createUrl('create')
);

$this->widget('AdminGridView', array(
    'id'           => 'page-grid',
    'dataProvider' => $model->search('our'),
    'filter'       => $model,
    'columns' => array(
        array(
            'name' => 'name',
            'type' => 'raw'
        ),

        'beginsell',
        array(
            'class'=>'CButtonColumn',
        ),
    ),
));
?>




