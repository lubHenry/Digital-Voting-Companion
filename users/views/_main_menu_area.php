<?php global $us_url_root;?>
<div class="main-menu-area mg-tb-40">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <ul class="nav nav-tabs notika-menu-wrap menu-it-icon-pro">
                    <li><a data-toggle="tab" href="#Home"><i class="fa fa-home"></i> Home</a>
                    </li>
                    <li><a data-toggle="tab" href="#mailbox"><i class="fa fa-money"></i> Transactions</a>
                    </li>
                    <li><a data-toggle="tab" href="#Interface"><i class="fa fa-bar-chart"></i> Statistics</a>
                    </li>
                </ul>
                <div class="tab-content custom-menu-content">
                    <div id="Home" class="tab-pane in notika-tab-menu-bg animated flipInX">
                        <ul class="notika-main-menu-dropdown">
                            <li><a href="<?=$us_url_root?>">Dashboard</a></li>
                            <li><a href="#">Profile</a></li>
                            <li><a href="#">Account Settings</a></li>
                        </ul>
                    </div>
                    <div id="mailbox" class="tab-pane notika-tab-menu-bg animated flipInX">
                        <ul class="notika-main-menu-dropdown">
                            <li><a href="<?=$us_url_root?>users/deposit.php">Deposit</a></li>
                            <li><a href="<?=$us_url_root?>users/send.php">Send</a></li>
                            <li><a href="<?=$us_url_root?>users/borrow_home.php">Borrow</a></li>
                            <li><a href="<?=$us_url_root?>users/groups.php">Group Schemes</a></li>
                            <li><a href="<?=$us_url_root?>users/invest_home.php"">Investment</a></li>
                        </ul>
                    </div>
                    <div id="Interface" class="tab-pane notika-tab-menu-bg animated flipInX">
                        <ul class="notika-main-menu-dropdown">
                            <li><a href="flot-charts.html">Flot Charts</a></li>
                            <li><a href="bar-charts.html">Bar Charts</a></li>
                            <li><a href="line-charts.html">Line Charts</a></li>
                            <li><a href="area-charts.html">Area Charts</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>