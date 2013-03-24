<?
$this->page_title = $page->title;

?>

    <?
    $this->renderPartial('_view', array(
        'data'    => $page
    ));
    ?>
