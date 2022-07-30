<?php

?>

<main class="d-flex align-items-center min-vh-100 py-3 py-md-0">
    <div class="container">
        <div class="card login-card">
            <div class="row no-gutters">
                <div class="col-md-5">
                    <img src="<?=$us_url_root?>usersc/templates/<?=$settings->template?>/assets/images/login.jpg" alt="login" class="login-card-img">
                </div>
                <div class="col-md-7">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 text-center">
                                <h1><?=lang("JOIN_SUC")?><?=$settings->site_name?></h1>
                                <p><?=lang("JOIN_THANKS");?></p>
                                <a href="<?=$us_url_root?>users/login.php" class="btn btn-primary"><?=lang("SIGNIN_TEXT")?></a>
                                <br /><br />
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</main>

