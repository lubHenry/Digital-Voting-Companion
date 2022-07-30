<?php global $form_method,$form_action,$userId,$productName,$lots,$accountDetails,$settings,$us_url_root,$abs_us_root,$errors,$successes,$validation,$productId,$price,$duration,$remaining_lots,$hooks,$fullName;?>
<div class="sale-statistic-area">
    <div class="container">
        <div class="row">
            <?=resultBlock($errors, $successes); ?>
            <?php if (!$validation->errors() == '') {?><div class="alert alert-danger"><?=display_errors($validation->errors()); ?></div><?php } ?>
            <?php includeHook($hooks, 'body'); ?>
            <div class="col-lg-9 col-md-8 col-sm-7 col-xs-12">
                <div class="form-element-list mg-t-30">
                    <div class="cmp-tb-hd">
                        <h2>Information Update</h2>
                        <p>Please, fill in all the fields below so as to effect your requests.</p>
                        <form action="<?=$form_action;?>" method="<?=$form_method;?>" id="cancel">

                        </form>
                    </div>
                    <form action="<?=$form_action;?>" method="<?=$form_method;?>" enctype="multipart/form-data" id="deposit_amount">
                        <fieldset class="sectionwrap">
                            <legend>Personal Information</legend>
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <div class="form-label-group outline" style="width:300px;">
                                        <input hidden type="text" name="pdtId" value="<?=$productId;?>"/>
                                        <input hidden type="text" name="pdtName" value="<?=$productName;?>"/>
                                        <input hidden type="text" name="pdtPrice" value="<?=$price;?>"/>
                                        <input hidden type="text" name="remLots" value="<?=$remaining_lots;?>"/>
                                        <input hidden type="text" name="lots" value="<?=$lots;?>"/>
                                        <input type="text" id="fullName" class="form-control form-control-lg shadow-none" style="width:300px" name="fullName" value="<?=$fullName;?>" placeholder="<?=lang("GEN_FULL_NAME")?>" />
                                        <span><label for="fullName" class="form-control-lg"><?=lang("GEN_FULL_NAME")?></label></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <?php
                                        $options = fetchTableOptions('maritalStatus');
                                    ?>
                                    <div class="form-label-group outline" style="width:200px;height:30px;">
                                        <select class="custom-select" id="marry" style="width:200px;height:30px;" name="marry">
                                            <?php
                                                //Cycle through the transactions
                                                foreach ($options as $opt) {
                                            ?>
                                            <option value="<?=$opt->statusCode;?>"><?=$opt->status;?></option>
                                            <?php }?>
                                        </select>
                                        <span><label for="marry" ><?=lang("GEN_MARRY_STATUS")?></label></span>
                                    </div>
                                    <input type="text" id="userId" hidden name="userId" value="<?=$userId;?>"/>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="sectionwrap">
                            <legend>Income Source</legend>
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <div class="form-label-group outline" style="width:300px;">
                                        <input type="text" id="empName" class="form-control form-control-lg shadow-none" style="width:300px" name="empName" placeholder="<?=lang("GEN_EMP_NAME")?>" />
                                        <span><label for="empName" class="form-control-lg"><?=lang("GEN_EMP_NAME")?></label></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <?php
                                    $emp_options = fetchTableOptions('employmentTypes');
                                    ?>
                                    <div class="form-label-group outline" style="width:250px;height:30px;">
                                        <select class="custom-select" id="employmentType" style="width:250px;height:30px;" name="employmentType">
                                            <option value="" selected>Select Employment Type</option>
                                            <?php
                                            //Cycle through the transactions
                                            foreach ($emp_options as $eOpt) {
                                                ?>
                                                <option value="<?=$eOpt->employmentCode;?>"><?=$eOpt->employmentType;?></option>
                                            <?php }?>
                                        </select>
                                        <span><label for="employmentType" ><?=lang("GEN_EMP_TYPE")?></label></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <?php
                                    $econ_options = fetchTableOptions('economicSector');
                                    ?>
                                    <div class="form-label-group outline" style="width:300px;height:30px;">
                                        <select class="custom-select" id="economicSector" style="width:300px;height:30px;" name="economicSector">
                                            <option value="" selected>Select Economic Sector</option>
                                            <?php
                                            //Cycle through the transactions
                                            foreach ($econ_options as $eCon) {
                                                ?>
                                                <option value="<?=$eCon->id;?>"><?=$eCon->economicSectorName;?></option>
                                            <?php }?>
                                        </select>
                                        <span><label for="economicSector" ><?=lang("GEN_ECON_SECTR")?></label></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <div class="form-label-group outline" style="width:300px;height:30px;">
                                        <select class="custom-select" id="subEconomicSector" style="width:300px;height:30px;" name="subEconomicSector">
                                        </select>
                                        <span><label for="subEconomicSector" ><?=lang("GEN_ECON_SUB_SECTR")?></label></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <div class="form-label-group outline" style="width:300px;">
                                        <input type="text" id="empAddress" class="form-control form-control-lg shadow-none" style="width:300px" name="empAddress" placeholder="<?=lang("GEN_EMP_ADDRESS")?>" />
                                        <span><label for="empAddress" class="form-control-lg"><?=lang("GEN_EMP_ADDRESS")?></label></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <div class="form-label-group outline" style="width:300px;">
                                        <input type="text" id="empContact" class="form-control form-control-lg shadow-none" style="width:300px" name="empContact" placeholder="<?=lang("GEN_EMP_CONTACT")?>" />
                                        <span><label for="empContact" class="form-control-lg"><?=lang("GEN_EMP_CONTACT")?></label></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <div class="form-label-group outline" style="width:200px;">
                                        <input type="date" class="form-control form-control-lg shadow-none" style="width:200px;" name="startDate" id="startDate"/>
                                        <span><label for="startDate" class="form-control-lg"><?=lang("GEN_START_DATE")?></label></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <?php
                                    $pay_options = fetchTableOptions('paymentFrequency');
                                    ?>
                                    <div class="form-label-group outline" style="width:300px;height:30px;">
                                        <select class="custom-select" id="incomeFreq" style="width:300px;height:30px;" name="incomeFreq">
                                            <option value="" selected>Select Your Income Frequency</option>
                                            <?php
                                            //Cycle through the transactions
                                            foreach ($pay_options as $pay) {
                                                ?>
                                                <option value="<?=$pay->paymentFrequencyCode;?>"><?=$pay->paymentFrequencyName;?></option>
                                            <?php }?>
                                        </select>
                                        <span><label for="incomeFreq" ><?=lang("INC_FREQUENCY")?></label></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <div class="form-label-group outline" style="width:250px;">
                                        <input type="text" id="income" class="form-control form-control-lg shadow-none" style="width:250px;" name="income" placeholder="Income Amount" />
                                        <span><label for="income" class="form-control-lg"><?=lang("GEN_INCOM_AMT")?></label></span>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="sectionwrap">
                            <legend>Upload Verification Documents</legend>
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <div class="form-label-group outline" style="width:250px;">
                                        <input type="file" id="empId" class="form-control form-control-lg shadow-none" style="width:250px;" name="empId" placeholder="<?=lang("GEN_EMP_PROOF")?>" />
                                        <span><label for="empId" class="form-control-lg"><?=lang("GEN_EMP_PROOF")?></label></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <div class="form-label-group outline" style="width:250px;">
                                        <input type="file" id="incomeProof" class="form-control form-control-lg shadow-none" style="width:250px;" name="incomeProof" placeholder="<?=lang("GEN_EMP_INCOM")?>" />
                                        <span><label for="incomeProof" class="form-control-lg"><?=lang("GEN_EMP_INCOM")?></label></span>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="sectionwrap">
                            <legend>Confirm & Update</legend>
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <div class="form-label-group outline" style="width:250px;">
                                        <input type="password" id="tt9" class="form-control form-control-lg shadow-none" style="width:250px;" name="password" placeholder="<?=lang("SIGNIN_PASS")?>" />
                                        <span><label for="tt9" class="form-control-lg"><?=lang("SIGNIN_PASS")?></label></span>
                                    </div>
                                </div>
                            </div>
                            <?php includeHook($hooks,'form');?>
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <div class="form-group ic-cmp-int float-lb form-elet-mg">
                                        <div class="material-design-btn" style="width:450px;">
                                            <input class="glow-button" type="submit" style="background-color: red;border: 0 none;color: #fff;text-align:center;padding: 5px 10px;width:100px;text-decoration: none;margin: 0px 0px;cursor: pointer;
                                    -webkit-border-radius: 5px;border-radius: 5px;transition: all 0.2s ease-in-out;" name="cancel" value="Cancel"/>
                                            <input type="hidden" value="<?=Token::generate();?>" name="csrf">
                                            <input class="glow-button" type="submit" style="background-color: green;border: 0 none;color: #fff;padding: 5px 10px;text-align:center;width:100px;text-decoration: none;margin: 4px 2px;cursor: pointer;
                                    -webkit-border-radius: 5px;border-radius: 5px;transition: all 0.2s ease-in-out;" value="Create" name="create" id="create"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                </div>
                </form>
            </div>

            <div class="col-lg-3 col-md-4 col-sm-5 col-xs-12">
                <?php require $abs_us_root.$us_url_root.'users/views/_innerContact.php';?>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#economicSector').change(function(){
            var cat_id=$('#economicSector').val();
            $('#subEconomicSector').empty(); //remove all existing options
            $.get('views/econSectorOpts.php',{'cat_id':cat_id},function(return_data){
                $.each(return_data.data, function(key,value){
                    $("#subEconomicSector").append("<option value=" + value.economicSectorActivityCode +">"+value.economicSectorActivityName+"</option>");
                });
            }, "json");
        });
    });
</script>
