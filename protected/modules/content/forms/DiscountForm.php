<?
return array(
    'enctype'              => 'multipart/form-data',
    'activeForm'           => array(
        'id'            => 'page-form',
        'clientOptions' => array('validateOnSubmit' => true),
    ),
    'elements'             => array(



        'name'    => array(
            'type' => 'text'
        ),

        'short_desc'    => array(
            'type' => 'text',
            //echo $form->textArea($model, 'Details', array('maxlength' => 300, 'rows' => 6, 'cols' => 50));
        ),

        'category_id' => array(
            'type'  => 'dropdownlist',
            'items' => CHtml::listData(Category::model()->findAll(), 'id', 'name'),
            'empty' => 'не выбран'
        ),
/*
        Yii::app()->controller->widget('application.components.formElements.ChosenAlex.ChosenWidget', array(
            'name'       => "pager_pages",
            'current'    => $value,
            'items'      => array_combine(range(10, 500, 5), range(10, 500, 5)),
            'htmlOptions'=> array(
                'style'=>'width:60px',
                'class' => 'pager_select',
                'model' => get_class($this->filter)
            )
        ));

    $this->widget('application.components.formElements.chosen.Chosen',array(
    'name' => 'metro', // input name
    'value' => '', // selection
    'multiple'=>true,
    'data' => $list,
    ));

*/
        'metros' => array(
            'type'  => 'application.components.formElements.chosen.Chosen',
            //'name' => 'metrospost', input name (не работает)
            //'value' =>  1,  selection (не работает)
            'multiple'=>true,
            'data' => CHtml::listData(Metro::model()->findAll(), 'id', 'name'),
            'htmlOptions' => array(
                'name' => 'Discount[metrosarray]', // принудительно присваиваем, иначе будет Discount[metros]
            )
        ),

        'gallery'    => array(
            'type'      => 'uploader_modal',
            'data_type' => 'image',
            'title'     => 'Фотогалерея'
        ),

        //дата акции
        'beginsell'    => array(
            'type' => 'date'
        ),

        'endsell'    => array(
            'type' => 'date',
           // 'value' => date('d.m.Y h:i', strtotime($model->date)),
        ),

        'beginvalid'    => array(
            'type' => 'date'
        ),

        'endvalid'    => array(
            'type' => 'date'
        ),

        //цена акции
        'price'    => array(
            'type' => 'text'
        ),

        'discount'    => array(
            'type' => 'text'
        ),

        'discountprice'    => array(
            'type' => 'text'
        ),

        'pricecoupon'    => array(
            'type' => 'text'
        ),

        //О компании. Вкладка адрес
        'company_name'    => array(
            'type' => 'text'
        ),

        'company_url'    => array(
            'type' => 'text'
        ),

        'company_tel'    => array(
            'type' => 'text'
        ),

        'company_address'    => array(
            'type' => 'text'
        ),

        'company_time'    => array(
            'type' => 'text'
        ),
        '<br><p>Узнать координаты можно <a  target="_blank" href="http://api.yandex.ru/maps/tools/getlonglat/">тут</a></p>',

        'company_coordinates'    => array(
            'type' => 'text'
        ),

        'cheat'    => array(
            'type' => 'text'
        ),

        'kuponator_exp'    => array(
            'type' => 'checkbox'
        ),

        /*
            'url' => array(
                'type'   => 'alias',
                'source' => 'title'
            ),
            'status'   => array(
                'type'  => 'dropdownlist',
                'items' => Page::$status_options
            ),
            'tags'     => array(
                'type'  => 'tags',
                'label' => 'Теги'
            ),
        */

        'text'     => array(
            'type' => 'editor'
        ),

        'description'    => array(
            'type' => 'editor',
            //echo $form->textArea($model, 'Details', array('maxlength' => 300, 'rows' => 6, 'cols' => 50));
        ),

        'MetaTag' =>array('type'=>'meta_tags'),
    ),
    'buttons'              => array(
        'submit' => array(
            'type'  => 'submit',
            'value' => t('сохранить')
        )
    )
);
