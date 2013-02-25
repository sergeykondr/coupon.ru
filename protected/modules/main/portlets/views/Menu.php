<div class="navbar">
    <div class="navbar-inner">
        <div class="container">
            <a data-target=".nav-collapse" data-toggle="collapse" class="btn btn-navbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <!--<div class="zalivka">123</div><div class="zalivka2">123</div>-->
            <a href="/" class="brand">Freeskidka</a>
            <div class="nav-collapse">
                <?
                $this->widget('BootMenu', array(
                    'items'       => $items
                ))
                ?>

            </div>
        </div>
    </div>
</div>


