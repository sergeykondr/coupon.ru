<?
$model_id = $model->model_id;

$this->widget('BootDetailView', array(
	'data' => $model,
	'attributes' => array(
		array('name' => 'model_id', 'value' => $model_id::name()),
		array(
            'name'  => 'object_id',
            'label' => 'Объект',
            'value' => $model->object ? $model->object : null
        ),
		array('name' => 'title'),
        array('name' => 'description'),
        array('name' => 'keywords'),
		array('name' => 'date_create'),
	),
)); 


