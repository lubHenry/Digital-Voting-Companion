<?php
global $abs_us_root,$us_url_root;
?>

<div class="sale-statistic-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-9 col-md-8 col-sm-7 col-xs-12">
                <div class="sale-statistic-inner notika-shadow mg-tb-30">
                    <div class="curved-inner-pro">
                        <div class="curved-ctn">
                            <h2>Historical Activity</h2>
                        </div>
                    </div>
                    <?php require $abs_us_root.$us_url_root.'users/views/_timeline.php';?>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-5 col-xs-12">
                <?php require $abs_us_root.$us_url_root.'users/views/_innerContact.php';?>
            </div>
        </div>
    </div>
</div>
