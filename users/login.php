<?php
global $abs_us_root,$us_url_root,$user,$settings;
ini_set("allow_url_fopen", 1);
require_once '../users/init.php';
require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
$hooks =  getMyHooks();
includeHook($hooks,'pre');
?>
<?php

$errors = $successes = [];
if (Input::get('err') != '') {
    $errors[] = Input::get('err');
}

if ($user->isLoggedIn()) {
    Redirect::to($us_url_root.$settings->redirect_uri_after_login);
}

if (!empty($_POST['login_hook'])) {
  $token = Input::get('csrf');
  if(!Token::check($token)){
    include($abs_us_root.$us_url_root.'usersc/scripts/token_error.php');
  }

      $validate = new Validate();
      $validation = $validate->check($_POST, array(
        'username' => array('display' => lang('GEN_UNAME'),'required' => true),
        'password' => array('display' => lang('GEN_PASS'), 'required' => true))
      );
      $validated = $validation->passed();
      // Set $validated to False to kill validation, or run additional checks, in your post hook
      $username = Input::get('username');
      $password = trim(Input::get('password'));
      $remember = false;
      includeHook($hooks,'post');

      if ($validated) {
        //Log user in
        $user = new User();
        $login = $user->loginEmail($username, $password, $remember);
        if ($login) {
          $hooks =  getMyHooks(['page'=>'loginSuccess']);
          includeHook($hooks,'body');
          $dest = sanitizedDest('dest');
              # if user was attempting to get to a page before login, go there
              $_SESSION['last_confirm']=date("Y-m-d H:i:s");

              if (!empty($dest)) {
                $redirect=Input::get('redirect');
                if(!empty($redirect) || $redirect!=='') Redirect::to($redirect);
                else Redirect::to($dest);
              } elseif (file_exists($abs_us_root.$us_url_root.'usersc/scripts/custom_login_script.php')) {

                # if site has custom login script, use it
                # Note that the custom_login_script.php normally contains a Redirect::to() call
                require_once $abs_us_root.$us_url_root.'usersc/scripts/custom_login_script.php';
              } else {
                if (($dest = Config::get('homepage')) ||
                ($dest = 'account.php')) {
                  Redirect::to($dest);
                }
              }

          } else {
            $eventhooks =  getMyHooks(['page'=>'loginFail']);
            includeHook($eventhooks,'body');
            logger("0","Login Fail","A failed login on login.php");
            $msg = lang("SIGNIN_FAIL");
            $msg2 = lang("SIGNIN_PLEASE_CHK");
            $errors[] = '<strong>'.$msg.'</strong>'.$msg2;
          }
        }else{
          $errors = $validation->errors();
        }
        sessionValMessages($errors, $successes, NULL);

    }
    if (empty($dest = sanitizedDest('dest'))) {
      $dest = '';
    }
    $token = Token::generate();
    ?>
    <main class="d-flex align-items-center min-vh-100 py-3 py-md-0">
        <div class="container">
            <div class="card login-card">
                <?php
                    includeHook($hooks,'body');
                ?>
                <div class="row no-gutters">
                    <div class="col-md-5">
                        <img src="<?=$us_url_root?>usersc/templates/<?=$settings->template?>/assets/images/login.jpg" alt="login" class="login-card-img">
                    </div>
                    <div class="col-md-7">
                        <div class="card-body">
                            <p class="login-card-description"><?=lang("SIGNIN_TITLE","");?></p>
                            <input type="hidden" name="dest" value="<?= $dest ?>" />
                            <form name="login" id="login-form" class="form-signin" action="" method="post">
                                <div class="form-group">
                                    <label for="email" class="sr-only"><?=lang("SIGNIN_UORE")?></label>
                                    <input type="text" name="username" id="username" class="form-control" placeholder="<?=lang("SIGNIN_UORE")?>">
                                </div>
                                <div class="form-group mb-4">
                                    <label for="password" class="sr-only"><?=lang("SIGNIN_PASS")?></label>
                                    <input type="password" class="form-control" name="password" id="password"  placeholder="<?=lang("SIGNIN_PASS")?>" required autocomplete="current-password">
                                </div>
                                <?php   includeHook($hooks,'form');?>
                                <input type="hidden" name="login_hook" value="1">
                                <input type="hidden" name="csrf" value="<?=$token?>">
                                <input type="hidden" name="redirect" value="<?=Input::get('redirect')?>" />
                                <button name="next_button" id="next_button" class="btn btn-block login-btn mb-4" type="submit" ><?=lang("SIGNIN_BUTTONTEXT","");?></button>
                            </form>
                            <a href="../users/forgot_password.php" class="forgot-password-link"><?=lang("SIGNIN_FORGOTPASS","");?></a>
                            <?php if($settings->registration==1) {?>
                            <p class="login-card-footer-text"><?=lang("SIGNIN_NO_ACCOUNT","");?> <a href="../users/join.php" class="text-reset"><?=lang("SIGNIN_REGISTER","");?></a></p>
                            <?php } ?>
                            <nav class="login-card-footer-nav">
                                <a href="#!"><?=lang("SIGNIN_TERMS","")?></a>
                                <a href="#!"><?=lang("SIGNIN_PRIVACY","")?></a>
                            </nav>
                        </div>
                        <?php   includeHook($hooks,'bottom');?>
                        <?php languageSwitcher();?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php require_once $abs_us_root.$us_url_root.'usersc/templates/'.$settings->template.'/container_close.php'; //custom template container ?>

        <!-- footers -->
    <?php require_once $abs_us_root.$us_url_root.'users/includes/page_footer.php'; // the final html footer copyright row + the external js calls ?>

        <!-- Place any per-page javascript here -->

    <?php require_once $abs_us_root.$us_url_root.'usersc/templates/'.$settings->template.'/footer.php'; //custom template footer?>
