<?php global $db,$userId,$cubFmt,$form_method,$form_action,$accountDetails,$settings,$us_url_root,$abs_us_root,$errors,$successes,$validation,$hooks;?>
<div class="sale-statistic-area">
    <div class="container">
        <div class="row">
            <?=resultBlock($errors, $successes); ?>
            <?php if (!$validation->errors() == '') {?><div class="alert alert-danger"><?=display_errors($validation->errors()); ?></div><?php } ?>
            <?php includeHook($hooks, 'body'); ?>
            <div class="col-lg-9 col-md-8 col-sm-7 col-xs-12">
                <?php
                $profitOrLoss = cust_profit_or_loss($userId);
                $underMgt = custAssetsUnderMgt($userId);
                $activeInvAcc = custActiveInveAcc($userId);
                $countryId = $accountDetails->countryId;
                $invRates = fetchInvestInterestRateDetails(null,$countryId);
                $stmt = listClientJoinedGroups($userId,null);
                ?>
                <div class="form-element-list mg-t-30">
                    <div class="cmp-tb-hd">
                        <h2>Groups</h2>
                    </div>
                    <div class="grid" data-masonry='{ "itemSelector": ".grid-item", "columnWidth": 160 }'>
                        <?php require_once $abs_us_root.$us_url_root.'users/views/_groups_menu.php';?>
                        <div class="grid-item grid-item--width5 grid-item--height4 table-responsive">
                            <h4>Groups you joined</h4>
                            <table id="data-table-basic" class="table table-striped">
                                <thead>
                                <tr>
                                    <th>Group Name</th>
                                    <th>Group Permissions</th>
                                    <th>Group Role</th>
                                    <th>Lots</th>
                                    <th>Amount</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                //Cycle through the transactions
                                foreach ($stmt as $v1) {
                                    ?>
                                    <tr>
                                        <?php
                                           // $idy = encryptString($v1->id);
                                           // $name = encryptString($v1->product_name);
                                            //$price = encryptString($v1->price);
                                            //$rem = encryptString($v1->remaining_lots);
                                            //$lot = encryptString($v1->lots);
                                        ?>
                                        <td><a href='joined_group.php?id=<?=$v1->id;?>&product_name=<?=$v1->product_name;?>&price=<?=$v1->price;?>&remaining_lots=<?=$v1->remaining_lots;?>&lots=<?=$v1->lots;?>'><?=$v1->product_name;?></a></td>
                                        <td><?php if($v1->user_level==1){ echo "Administrator";} else if($v1->user_level==2){echo "User";}?></td>
                                        <td><?php if($v1->group_role==2){ echo "Board Member";} else if($v1->group_role==3){echo "Treasurer";} else if ($v1->group_role==5){echo "Member";}?></td>
                                        <td><?=$v1->lots;?></td>
                                        <td><?=$v1->amount;?></td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                                <div id="item1" class="item item--mod">
                                    <h3><?=$v1->product_name;?> options.</h3>
                                    <p><a href="#">Increase Lots</a></p>
                                    <p><a href="#">Loan Application</a></p>
                                    <p><a href="#">Pay in Advance</a></p>
                                    <p><a href="#">Leave Group</a></p>
                                    <img src="<?=$us_url_root?>users/images/happy-face.jpg"/>
                                    <b class="item-close js-popup-close" onclick="window.location.reload();">x</b>
                                </div>
                                <tr>
                                    <th>Group Name</th>
                                    <th>Group Permissions</th>
                                    <th>Group Role</th>
                                    <th>Lots</th>
                                    <th>Amount</th>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-lg-3 col-md-4 col-sm-5 col-xs-12">
                <?php require $abs_us_root.$us_url_root.'users/views/_innerContact.php';?>
            </div>
        </div>
    </div>
</div>
