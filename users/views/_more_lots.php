<?php global $form_method,$form_action,$product_name,$price,$remaining_lots,$accountDetails,$settings,$us_url_root,$abs_us_root,$errors,$successes,$validation,$productId,$price,$duration,$remaining_lots,$hooks;?>
<div class="sale-statistic-area">
    <div class="container">
        <div class="row">
            <?=resultBlock($errors, $successes); ?>
            <?php if (!$validation->errors() == '') {?><div class="alert alert-danger"><?=display_errors($validation->errors()); ?></div><?php } ?>
            <?php includeHook($hooks, 'body'); ?>
            <div class="col-lg-9 col-md-8 col-sm-7 col-xs-12">
                <div class="form-element-list mg-t-30">
                    <div class="cmp-tb-hd">
                        <h2>Increase lots in <?=$product_name;?></h2>
                        <p>Please, fill in all the fields below so as to effect your increase.</p>
                        <p><br/></p>
                        <p><?php echo "'".$product_name."'"." has ".number_format($remaining_lots)." remaining lots at ".number_format($price)." each." ;?></p>
                    </div>
                    <form action="<?=$form_action;?>" method="<?=$form_method;?>" id="social_invest">
                        <div class="row">
                            <div class="form-group ic-cmp-int float-lb floating-lb">
                                <div class="nk-int-st">
                                    <input type="text" hidden name="sendAcc" id="sendAcc" value="<?=$accountDetails->accountNumber;?>" class="form-control"/>
                                    <input type="text" hidden name="pdtId" id="pdtId" value="<?=$productId;?>" class="form-control"/>
                                    <input type="text" hidden name="price" id="price" value="<?=$price;?>" class="form-control"/>
                                    <input type="text" hidden name="remaining_lots" id="remaining_lots" value="<?=$remaining_lots;?>" class="form-control"/>
                                    <input type="text" hidden name="pdtName" id="pdtName" value="<?=$product_name;?>" class="form-control"/>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <div class="form-label-group outline">
                                    <input type="text" class="form-control form-control-lg shadow-none" name="lots" id="lots" placeholder="Number Of Lots" />
                                    <span><label for="lots" class="form-control-lg"><?=lang("GEN_LOT_NOs")?></label></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <div class="form-label-group outline">
                                    <input type="password" class="form-control form-control-lg shadow-none" name="password" id="password" placeholder="<?=lang("SIGNIN_PASS")?>" />
                                    <span><label for="password" class="form-control-lg"><?=lang("SIGNIN_PASS")?></label></span>
                                </div>
                            </div>
                        </div>

                        <?php includeHook($hooks,'form');?>
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group ic-cmp-int float-lb form-elet-mg">
                                    <div class="material-design-btn">
                                        <input class="glow-button" type="submit" style="background-color: red;border: 0 none;color: #fff;text-align:center;padding: 5px 10px;width:100px;text-decoration: none;margin: 0px 0px;cursor: pointer;
                                    -webkit-border-radius: 5px;border-radius: 5px;transition: all 0.2s ease-in-out;" name="cancel" value="Cancel"/>
                                        <input type="hidden" value="<?=Token::generate();?>" name="csrf">
                                        <input class="glow-button" type="submit" style="background-color: green;border: 0 none;color: #fff;padding: 5px 10px;text-align:center;width:100px;text-decoration: none;margin: 4px 2px;cursor: pointer;
                                    -webkit-border-radius: 5px;border-radius: 5px;transition: all 0.2s ease-in-out;" value="Invest" name="invest" id="invest"/>
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
