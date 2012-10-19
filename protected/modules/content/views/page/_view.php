
<div class="page">
    <? if ($preview): ?>
    <h1 class="page-title">
        <?= CHtml::link(CHtml::encode($data->title), $data->href, array('class' => 'page-title')); ?>

        <? if (Yii::app()->user->checkAccess('Page_update')): ?>
        <?=
        $link = CHtml::link(
            "",
            $data->update_url,
            array(
                'class' => 'page-update  glyphicon-pencil',
                'title' => 'редактировать'
            )
        );
        ?>
        <? endif ?>
    </h1>
    <? endif ?>

    <? if ($data->sections): ?>
    <div class="sections">
        <span class="glyphicon-briefcase"></span>
        <? foreach ($data->sections as $i => $section): ?>
        <? if ($i > 0): ?>,  <? endif; ?>
        <?= CHtml::link($section->name, $this->createUrl('/page/section/' . $section->id), array('class' => 'section', 'title' => $section->name)); ?>
        <? endforeach ?>
    </div>
    <? endif ?>

    <? if (isset($preview) && $preview): ?>
    <?php
    $parts = explode('{{cut}}', $data->text);
    echo array_shift($parts);
    ?>

    <p><?= CHtml::link('читать далее →', $data->href, array('class' => 'read-more')) ?></p>
    <? else: ?>
    <?= $data->text ?>
    <? endif ?>

    <? if ($data->tags): ?>
    <ul class="tags">
        <li><span class="glyphicon-tags"></span></li>

        <? foreach ($data->tags as $i => $tag): ?>
        <? if ($i > 0): ?>, <? endif ?>
        <li><a rel="tag" href="<?= $this->createUrl("/pages/tag/{$tag->name}"); ?>"><?= $tag->name ?></a></li>
        <? endforeach ?>
    </ul>
    <? endif ?>

    <div class="infopanel">
        <div class="voting">
            <? $this->widget('social.portlets.RatingPortlet', array('model' => $data)); ?>
        </div>

        <div class="published"><?= Yii::app()->dateFormatter->formatDateTime($data->date_create, 'long', 'short') ?></div>

        <!--        --><?// $this->widget('social.portlets.FavoritePortlet', array('model' => $data)); ?>

        <div class="author">
            <!--            <a href="--><?//= $data->user->href ?><!--" title="Автор текста">--><?//= $data->user->name ?><!--</a>-->
            <!--            <span title="рейтинг пользователя" class="rating">--><?//= $data->user->rating ?><!--</span>-->
        </div>

        <div class="comments">
            <span class="glyphicon-comments"></span>
            <a href="<?= $data->href ?>#comments" title="Читать комментарии"><span class="all"><?= $data->comments_count ?></span> </a>
        </div>
    </div>
</div>
<br clear="all"/>

<? if (!$preview): ?>
<!--    --><?// $this->widget('CommentsPortlet', array('model' => $data)); ?>
<? endif ?>



<?

//echo $data->getServerPath();

foreach ($data->files as $gal)
{
  //  echo 222;
//echo $gal->getHref();

    echo $gal->path . '     /       ';
    echo $gal->name;

    echo CHtml::image($gal->getHref());
    //echo $gal->path;
}

?>
