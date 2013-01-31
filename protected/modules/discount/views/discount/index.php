<? $this->page_title = null; ?>
<div class="row-fluid">
    <?
    $this->widget('ListView', array(
        'id'           => 'Page-listView',
        'dataProvider' => $data_provider,
        'summaryText'  => '',
        'itemView'     => '_view',
        'viewData'     => array('preview' => true),
        'emptyText'    => t('Акции еще не были добавлены')
    ));
    ?>

</div>
<div class="row-fluid">
    <? if  (isset($text))
    echo $text;
    ?>
    crop
</div>