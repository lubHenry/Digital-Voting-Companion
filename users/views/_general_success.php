<?php global $form_method,$form_action,$info,$product_name,$price,$lots,$accountDetails,$settings,$us_url_root,$abs_us_root,$errors,$successes,$validation,$productId,$price,$duration,$remaining_lots,$hooks;?>
<div class="sale-statistic-area">
    <div class="container">
        <div class="row">
            <?=resultBlock($errors, $successes); ?>
            <?php if (!$validation->errors() == '') {?><div class="alert alert-danger"><?=display_errors($validation->errors()); ?></div><?php } ?>
            <?php includeHook($hooks, 'body'); ?>
            <div class="col-lg-9 col-md-8 col-sm-7 col-xs-12">
                <div class="form-element-list mg-t-30">
                    <div class="cmp-tb-hd">
                        <h2>Successful</h2>
                        <p><?=$info;?></p>
                        <p><br/></p>
                    </div>
                    <form action="<?=$form_action;?>" method="<?=$form_method;?>" id="transaction_success">
                        <?php includeHook($hooks,'form');?>
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group ic-cmp-int float-lb form-elet-mg">
                                    <div class="material-design-btn">
                                        <input type="hidden" value="<?=Token::generate();?>" name="csrf">
                                        <input class="glow-button" type="submit" style="background-color: green;border: 0 none;color: #fff;padding: 5px 10px;text-align:center;width:100px;text-decoration: none;margin: 4px 2px;cursor: pointer;
                                    -webkit-border-radius: 5px;border-radius: 5px;transition: all 0.2s ease-in-out;" value="Proceed" name="invest" id="invest"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
                </form>
            </div>

            <div class="col-lg-3 col-md-4 col-sm-5 col-xs-12">
                <?php require $abs_us_root.$us_url_root.'users/views/_innerContact.php';?>
            </div>
        </div>
    </div>
</div>
