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

        'category_id' => array(
            'type'  => 'dropdownlist',
            'items' => CHtml::listData(Category::model()->findAll(), 'id', 'name'),
            'empty' => 'не выбран'
        ),

        'gallery'    => array(
            'type'      => 'uploader_modal',
            'data_type' => 'image',
            'title'     => 'Фотогалерея'
        ),


        'description'    => array(
            'type' => 'editor'
        ),
        'description'    => array(
            'type' => 'editor'
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

        'company_coordinates'    => array(
            'type' => 'text'
        ),

        'cheat'    => array(
            'type' => 'text'
        ),

        'xml_kuponator'    => array(
            'type' => 'checkbox'
        ),
        'hint' => '<h2>HFP</h2>',

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
    ),
    'buttons'              => array(
        'submit' => array(
            'type'  => 'submit',
            'value' => t('сохранить')
        )
    )
);
