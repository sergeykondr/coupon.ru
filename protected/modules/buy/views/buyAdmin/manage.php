<?
$this->widget('AdminGridView', array(
    'id'           => 'page-grid',
    'dataProvider' => $model->search(),
    'filter'       => $model,
    'columns' => array(
        array(
            'name' => 'id',
            'type' => 'raw',
            'header'=>'id покупки',
        ),

        'email',
        'date',
        'discount_id',

        /*
        array
        (
            'name'=>'date',
            'htmlOptions'=>array('style'=>'text-align: center'),
            'value'=>'date_format(date_create($data->date), "Y-m-d H:i:s")',
        ),
        */

        /*
        array(
            'class'=>'CButtonColumn',
        ),
        */
    ),
));
?>




