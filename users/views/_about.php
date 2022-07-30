<?php global $settings,$form_valid,$username,$email,$hooks,$abs_us_root,$us_url_root,$validation,$form_action,$form_method,$fname,$lname;?>
<div class="row">
    <div class="login-content">
        <?php
        if (!$form_valid && Input::exists()){?>
            <?php if(!$validation->errors()=='') {?><div class="alert alert-danger"><?=display_errors($validation->errors());?></div><?php } ?>
        <?php }
        includeHook($hooks,'body');
        ?>
        <!-- Register -->
        <div class="nk-block toggled" id="l-register">
            <form action="<?=$form_action;?>" method="<?=$form_method;?>" id="about">
                <div class="nk-form">
                    <fieldset class="sectionwrap">
                        <legend>Our Mission</legend>
                        <p>We Strive to create an environment that allows incubation of ideas into products/services
                        that are desired allover the globe, as the best quality, advanced, secure with a cutting age.</p>
                        <p>We shall be respected by our competitors and considered as role models to all nations, tongues
                         and beliefs of the entire universe.</p>
                    </fieldset>
                    <fieldset class="sectionwrap">
                        <legend>Our Vision</legend>
                        <p>To be the champion in creation, development and implementation of innovative ideas
                        from Africa to the entire world.</p>
                        <legend>Our Purpose</legend>
                        <p>To simplify the way of life for humanity through continuous innovations.</p>
                    </fieldset>
                    <fieldset class="sectionwrap">
                        <legend>Our Values</legend>
                            <p>
                                <ul>
                                    <li>Integrity and Honesty</li>
                                    <li>Leading-edge innovation</li>
                                    <li>Excellent Customer Service</li>
                                    <li>Respect</li>
                                    <li>Speed And Agility</li>
                                    <li>Team Work</li>
                                    <li>Responsibility</li>
                               </ul>
                            </p>
                        <legend>Corporate Objectives</legend>
                        <p>
                            <ul>
                                <li>Commitment to employees</li>
                                <li>Market Leadership</li>
                                <li>Profit</li>
                                <li>Customer Loyalty</li>
                                <li>Professionalism</li>
                            </ul>
                        </p>
                    </fieldset>
                    <fieldset class="sectionwrap">
                        <legend>Our Product(s)</legend>
                        <div class="row">
                           <legend>Urenah</legend>
                            <div style="text-align: center;">
                                <p>Do you have a dream of allowing your money to work for you,
                                BUT has always been limited by how small it is?</p>
                                <p>Well, fret no more. With Urenah, we allow you to team up with like minded people,
                                allow your money to grow, while creating a large pool from which any team member can
                                scoop a portion and use in times of need. Upon paying it back, the entire team benefits from
                                the returns accumulated within the pool after a specified cycle.</p>
                            </div>
                        </div>
                    </fieldset>

                </div>
            </form>
            <div class="nk-navigation rg-ic-stl">
                <a href="<?=$us_url_root?>users/login.php" data-ma-action="nk-login-switch" data-ma-block="#l-login"><i class="fa fa-home"></i> <span>Home</span></a>
            </div>
        </div>
    </div>
</div>
