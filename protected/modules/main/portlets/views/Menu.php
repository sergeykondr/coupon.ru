<div class="navbar">
    <div class="navbar-inner">
        <div class="container">
            <a data-target=".nav-collapse" data-toggle="collapse" class="btn btn-navbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <a href="/" class="brand">coupon.ru</a>
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


