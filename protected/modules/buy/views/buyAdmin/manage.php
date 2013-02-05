
<?
echo 'Покупка - это сгенерированный купон. На каждом купоне есть код.
<br>Пример кода купона: 210 - 5 - 1.
<br>где: 210 - шифр покупки; 5 - id категории; 1 - номер покупателя данного дискаунта';
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
        array(
            'name' => 'cypher',
            'type' => 'raw',
            'header'=>'Шифр id покупки',
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




