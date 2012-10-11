<?
return array(
    'enctype'              => 'multipart/form-data',
    'activeForm'           => array(
        'id'            => 'page-form',
        'clientOptions' => array('validateOnSubmit' => true),
    ),
    'elements'             => array(
        'title'    => array(
            'type' => 'text'
        ),
        /*
        'category_id'    => array(
            'type' => 'text'
        ),
        */
        'category_id' => array(
            'type'  => 'dropdownlist',
            'items' => CHtml::listData(Category::model()->findAll(), 'id', 'name'),
            'empty' => 'не выбран'
        ),

        'gallery'    => array(
            'type'      => 'uploader_modal',
            'data_type' => 'image',
            'title'     => 'Файлы'
        ),

        'caption'    => array(
            'type' => 'text'
        ),
        'text'    => array(
            'type' => 'text'
        ),
//        'url' => array(
//            'type'   => 'alias',
//            'source' => 'title'
//        ),



    /*

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
