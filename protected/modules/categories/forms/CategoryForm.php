<?
return array(
    'activeForm'           => array(
        'id'            => 'page-form',
        'clientOptions' => array('validateOnSubmit' => true),
    ),
    'elements'             => array(
        'MetaTag' =>array('type'=>'meta_tags'),

        'name'    => array(
            'type' => 'text'
        ),

        'text'     => array(
            'type' => 'editor'
        ),

    ),
    'buttons' => array(
        'submit' => array(
            'type'  => 'submit',
            'value' => 'сохранить'
        )
    )
);
