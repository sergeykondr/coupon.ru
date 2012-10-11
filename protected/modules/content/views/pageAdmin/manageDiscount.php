<?
$this->tabs = array(
    'Добавить страницу' => $this->createUrl('create')
);

$this->widget('AdminGridView', array(
    'id'           => 'page-grid',
    'dataProvider' => $model->search(),
    'filter'       => $model,
    'columns' => array(
        array(
            'name' => 'title',
            'type' => 'raw'
        ),

        'date',
        array(
            'class'=>'CButtonColumn',
        ),
    ),
));
?>




