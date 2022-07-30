<?php global $us_url_root;?>
<div class="mobile-menu-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="mobile-menu">
                    <nav id="dropdown">
                        <ul class="mobile-menu-nav">
                            <li><a data-toggle="collapse" data-target="#Charts" href="#">Home</a>
                                <ul class="collapse dropdown-header-top">
                                    <li><a href="<?=$us_url_root?>">Dashboard</a></li>
                                    <li><a href="#">Profile</a></li>
                                    <li><a href="index-2.html">Account Settings</a></li>
                                </ul>
                            </li>
                            <li><a data-toggle="collapse" data-target="#demoevent" href="#">Transactions</a>
                                <ul id="demoevent" class="collapse dropdown-header-top">
                                    <li><a href="<?=$us_url_root?>users/deposit.php">Deposit</a></li>
                                    <li><a href="<?=$us_url_root?>users/send.php">Send</a></li>
                                    <li><a href="<?=$us_url_root?>users/borrow_home.php">Borrow</a></li>
                                    <li><a href="<?=$us_url_root?>users/security.php">Group Schemes</a></li>
                                    <li><a href="<?=$us_url_root?>users/invest_home.php"">Investment</a></li>
                                </ul>
                            </li>
                            <li><a data-toggle="collapse" data-target="#demolibra" href="#">Statistics</a>
                                <ul id="demolibra" class="collapse dropdown-header-top">
                                    <li><a href="flot-charts.html">Flot Charts</a></li>
                                    <li><a href="bar-charts.html">Bar Charts</a></li>
                                    <li><a href="line-charts.html">Line Charts</a></li>
                                    <li><a href="area-charts.html">Area Charts</a></li>
                                </ul>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>