
<div class="page">
</div>
<br>
<?

//echo $data->getServerPath();

foreach ($data->files as $gal)
{

    echo $gal->path . '     /       ';
    echo $gal->name;
    echo CHtml::image($gal->getHref());
    //echo $gal->path;
}

?>
