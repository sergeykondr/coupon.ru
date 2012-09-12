<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><? echo $this->meta_title ?></title>
    <meta name="description" content="<? echo $this->meta_description ?>">
    <meta name="keywords" content="<? echo $this->meta_keywords ?>">
    <meta name="author" content="">

    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <?
    $base = Yii::app()->baseUrl;

    $cs = Yii::app()->clientScript;
    $cs->registerCoreScript('jquery');
    Yii::app()->bootstrap->registerScripts();

    $cs->registerCssFile($base.'/css/icons.css');
    $cs->registerCssFile($base.'/css/site/form.css');
    $cs->registerCssFile($base.'/css/site/style.css');
    $cs->registerCssFile($base.'/css/site/menu.css');
    $cs->registerCssFile($base.'/css/site/page.css');
    $cs->registerCssFile($base.'/css/site/comments.css');
    $cs->registerCssFile($base.'/css/site/favorites.css');
    $cs->registerCssFile($base.'/css/site/rating.css');
    $cs->registerScriptFile($base.'/js/site/modal-windows.js');

//    $cs->registerCssFile(Yii::app()->assetManager->publish(Yii::getPathOfAlias('webroot.css.site.styles') . '.less'));
//    $cs->registerCssFile((Yii::app()->clientScript->getCoreScriptUrl() . '/jui/css/base/jquery-ui.css'));
//    $cs->registerScriptFile('/js/plugins/modal/bootstrap-modal.js');
//    $cs->registerCssFile('/js/plugins/modal/modal.css');
//    $cs->registerScriptFile('/js/plugins/blockUI/blockUI.js');
//    $cs->registerScriptFile('/js/plugins/blockUI/loaders.js');
//    $cs->registerScriptFile('/js/plugins/bootstrap/bootstrap-modal.js');
//    if (YII_DEBUG)
//    {
//        $cs->registerScriptFile('/js/plugins/debug.js');
//    }


    ?>

    <link rel="shortcut icon" href="/favicon.ico">

</head>

<body>

<? $this->renderPartial('application.views.layouts._modal'); ?>

<div id='main-wrapper'>

    <? $this->widget('Menu'); ?>
    <? $this->widget('SubMenu'); ?>

    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span8 well">
                <? if ($this->page_title): ?>
                    <h1><?= $this->page_title ?></h1>
                <? endif ?>

                <? foreach (Yii::app()->user->getFlashes() as $type => $msg): ?>
                    <div class="alert alert-<?= $type ?>"><?= $msg ?></div>
                <? endforeach ?>

                <?= $content ?>
            </div>
            <!--/span-->
            <div class="span4 sidebar-manager">
                <?= $this->widget('SidebarManager') ?>
            </div>
        </div>
        <hr>

        <footer>
            <p>&copy; Company 2012</p>
        </footer>
    </div>

</div>
