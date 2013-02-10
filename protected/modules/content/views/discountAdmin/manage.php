<?
$this->tabs = array(
    'Добавить дискаунт' => $this->createUrl('create')
);

$this->widget('AdminGridView', array(
    'id'           => 'page-grid',
    'dataProvider' => $model->search(),
    'filter'       => $model,
    'columns' => array(
        'id',
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




