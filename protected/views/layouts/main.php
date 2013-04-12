<!DOCTYPE html>
<html lang="en">
<head>
    <title><? echo $this->pageTitle ?></title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <? /*<meta name="description" content="< echo $this->meta_description >">
    <meta name="keywords" content="< echo $this->meta_keywords >">
    <meta name="author" content=""> */?>
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
    $cs->registerCssFile($base.'/css/site/discount.css');
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
<? echo Yii::app()->baseUrl; ?>
<? $this->renderPartial('application.views.layouts._modal'); ?>

<div id='main-wrapper'>

    <? $this->widget('Menu'); ?>
    <? $this->widget('SubMenu'); ?>

    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span12 well">
                <? if ($this->page_title): ?>
                <h1><?= $this->page_title ?></h1>
                <? endif ?>

                <? foreach (Yii::app()->user->getFlashes() as $type => $msg): ?>
                <div class="alert alert-<?= $type ?>"><?= $msg ?></div>
                <? endforeach ?>

                <?= $content ?>
            </div>
            <!--/span
            <div class="span4 sidebar-manager">
                <= $this->widget('SidebarManager') >
            </div>
            -->
        </div>
        <hr>
        <style>
            .footer_block {
                padding-left: 80px
            }
        </style>
        <footer>
            <div class="row-fluid">
                <div class="span12 well">
                    <div class="span4 footer_block"">
                    <strong>О компании</strong>
                    <ul>
                        <li><a href="/page/1">О нас</a></li>
                        <li><a href="/page/2">Контакты</a></li>
                    </ul>
                </div>
                <div class="span4 footer_block">
                    <strong>Партнерам</strong>
                    <ul>
                        <li><a href="/page/3">Для бизнеса</a></li>
                    </ul>
                </div>
                <div class="span4 footer_block">
                    <strong>Поддержка</strong>
                    <ul>
                        <li><a href="/page/4">Задать вопрос</a></li>
                        <li><a href="/page/5">Публичная оферта</a></li>
                    </ul>
                </div>
            </div>
    </div>
    </footer>
</div>
<!-- Yandex.Metrika counter -->
<script type="text/javascript">
    (function (d, w, c) {
        (w[c] = w[c] || []).push(function() {
            try {
                w.yaCounter20127475 = new Ya.Metrika({id:20127475,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true});
            } catch(e) { }
        });

        var n = d.getElementsByTagName("script")[0],
                s = d.createElement("script"),
                f = function () { n.parentNode.insertBefore(s, n); };
        s.type = "text/javascript";
        s.async = true;
        s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

        if (w.opera == "[object Opera]") {
            d.addEventListener("DOMContentLoaded", f, false);
        } else { f(); }
    })(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="//mc.yandex.ru/watch/20127475" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
</body>
