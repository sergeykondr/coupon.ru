<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8"/>
    <title>Yii CMS Admin Panel</title>

    <?
    $base = Yii::app()->baseUrl;

    $cs = Yii::app()->clientScript;
    $cs->registerCoreScript('jquery');
    $cs->registerCoreScript('jquery.ui');
    Yii::app()->bootstrap->registerScripts();
    $cs->registerCssFile($base.'/css/admin/layout.css');
    $cs->registerCssFile($base.'/css/admin/form.css');
    $cs->registerCssFile($base.'/css/admin/extend.css');
    $cs->registerScriptFile($base.'/js/admin/hideshow.js');
    $cs->registerScriptFile($base.'/js/admin/jquery.tablesorter.min.js');
    $cs->registerScriptFile($base.'/js/admin/jquery.equalHeight.js');
    $cs->registerScriptFile($base.'/js/admin/main.js');
    ?>

    <!--[if lt IE 9]>
    <link rel="stylesheet" href="<?= $base ?>/css/admin/ie.css" type="text/css" media="screen"/>
    <script src="<?= $base ?>/js/admin/html5.js"></script>
    <![endif]-->

    <style type="text/css">
        .middle_div
        {
           position: absolute;
           width: 470px !important;
           left: 50%;
           top: 50%;
           margin-left: -235px;
           margin-top: -200px;
        }
    </style>
</head>


<body>
    <header id="header">
        <hgroup>
            <h2 class="section_title" style="width: 100%;text-align: center"><? echo t('Панель управления : Авторизация'); ?></h2>
        </hgroup>
    </header>

    <article class="module middle_div">
        <div style="padding: 10px!important;">
            <? echo $content; ?>
        </div>
        <? if ($this->footer): ?>
            <footer><? echo $this->footer; ?></footer>
        <? endif ?>
    </article>
</body>

</html>