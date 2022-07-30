<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
ini_set('allow_url_fopen', 1);
require_once '../users/init.php';
require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';

?>

<?php if (!securePage($_SERVER['PHP_SELF'])) {
    die();
}
$hooks = getMyHooks();

if ($user->isLoggedIn()) {
    Redirect::to($us_url_root.'index.php');
}

includeHook($hooks, 'pre');
//There is a lot of commented out code for a future release of sign ups with payments
$form_method = 'POST';
$form_action = 'join.php';
$vericode = randomstring(15);

$form_valid = false;

//Decide whether or not to use email activation
$query = $db->query('SELECT * FROM email');
$results = $query->first();
$act = $results->email_act;

//If you say in email settings that you do NOT want email activation,
//new users are active in the database, otherwise they will become
//active after verifying their email.
if ($act == 1) {
    $pre = 0;
} else {
    $pre = 1;
}

if (Input::exists()) {
    $token = $_POST['csrf'];
    if (!Token::check($token)) {
        include $abs_us_root.$us_url_root.'usersc/scripts/token_error.php';
    }

    $fname = Input::get('fname');
    $lname = Input::get('lname');
    $email = Input::get('email');
    $username = Input::get('username');


    $validation = new Validate();
        if (pluginActive('userInfo', true)) {
            $is_not_email = false;
        } else {
            $is_not_email = true;
        }
        $validation->check($_POST, [
          'username' => [
                'display' => lang('GEN_UNAME'),
                'is_not_email' => $is_not_email,
                'required' => true,
                'min' => $settings->min_un,
                'max' => $settings->max_un,
                'unique' => 'users',
          ],
          'fname' => [
                'display' => lang('GEN_FNAME'),
                'required' => true,
                'min' => 1,
                'max' => 60,
          ],
          'lname' => [
                'display' => lang('GEN_LNAME'),
                'required' => true,
                'min' => 1,
                'max' => 60,
          ],
          'email' => [
                'display' => lang('GEN_EMAIL'),
                'required' => true,
                'valid_email' => true,
                'unique' => 'users',
                'min' => 5,
                'max' => 100,
          ],

          'password' => [
                'display' => lang('GEN_PASS'),
                'required' => true,
                'min' => $settings->min_pw,
                'max' => $settings->max_pw,
          ],
          'confirm' => [
                'display' => lang('PW_CONF'),
                'required' => true,
                'matches' => 'password',
          ],
        ]);
    if ($eventhooks = getMyHooks(['page' => 'joinAttempt'])) {
        includeHook($eventhooks, 'body');
    }
    if ($validation->passed()) {
            $form_valid = true;
            //add user to the database
            $user = new User();
            $join_date = date('Y-m-d H:i:s');
            $params = [
                                'fname' => Input::get('fname'),
                                'email' => $email,
                                'username' => $username,
                                'vericode' => $vericode,
                                'join_vericode_expiry' => $settings->join_vericode_expiry,
                        ];
            $vericode_expiry = date('Y-m-d H:i:s');
            if ($act == 1) {
                //Verify email address settings
                $to = rawurlencode($email);
                $subject = html_entity_decode($settings->site_name, ENT_QUOTES);
                $body = email_body('_email_template_verify.php', $params);
                email($to, $subject, $body);
                $vericode_expiry = date('Y-m-d H:i:s', strtotime("+$settings->join_vericode_expiry hours", strtotime(date('Y-m-d H:i:s'))));
            }
            try {
                // echo "Trying to create user";
                if(isset($_SESSION['us_lang'])){
                  $newLang = $_SESSION['us_lang'];
                }else{
                  $newLang = $settings->default_language;
                }
                $fields = [
                                        'username' => $username,
                                        'fname' => ucfirst(Input::get('fname')),
                                        'lname' => ucfirst(Input::get('lname')),
                                        'email' => Input::get('email'),
                                        'password' => password_hash(Input::get('password', true), PASSWORD_BCRYPT, ['cost' => 12]),
                                        'permissions' => 1,
                                        'join_date' => $join_date,
                                        'email_verified' => $pre,
                                        'vericode' => $vericode,
                                        'vericode_expiry' => $vericode_expiry,
                                        'oauth_tos_accepted' => true,
                                        'language'=>$newLang,
                                ];
                $activeCheck = $db->query('SELECT active FROM users');
                if (!$activeCheck->error()) {
                    $fields['active'] = 1;
                }
                $theNewId = $user->create($fields);

                includeHook($hooks, 'post');
            } catch (Exception $e) {
                if ($eventhooks = getMyHooks(['page' => 'joinFail'])) {
                    includeHook($eventhooks, 'body');
                }
                die($e->getMessage());
            }
            if ($form_valid == true) { //this allows the plugin hook to kill the post but it must delete the created user
                include $abs_us_root.$us_url_root.'usersc/scripts/during_user_creation.php';

                if ($act == 1) {
                    logger($theNewId, 'User', 'Registration completed and verification email sent.');
                    $query = $db->query('SELECT * FROM email');
                    $results = $query->first();
                    $act = $results->email_act;
                    require $abs_us_root.$us_url_root.'users/views/_joinThankYou_verify.php';

                } else {
                    logger($theNewId, 'User', 'Registration completed.');
                    if (file_exists($abs_us_root.$us_url_root.'usersc/views/_joinThankYou.php')) {
                        require_once $abs_us_root.$us_url_root.'usersc/views/_joinThankYou.php';
                    } else {
                        require $abs_us_root.$us_url_root.'users/views/_joinThankYou.php';
                    }

                }
                require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php';
                die();
            }

    } //Validation
} //Input exists

?>
<?php header('X-Frame-Options: DENY'); ?>
<div id="page-wrapper">
<div class="container">
<?php
if ($settings->registration == 1) {
    if(file_exists($abs_us_root.$us_url_root.'usersc/views/_join.php')){
      require($abs_us_root.$us_url_root.'usersc/views/_join.php');
    }else{
      require $abs_us_root.$us_url_root.'users/views/_join.php';
    }

} else {
  if(file_exists($abs_us_root.$us_url_root.'usersc/views/_joinDisabled.php')){
    require $abs_us_root.$us_url_root.'usersc/views/_joinDisabled.php';
  }else{
    require $abs_us_root.$us_url_root.'users/views/_joinDisabled.php';
  }
}
includeHook($hooks, 'bottom');
?>

</div>
</div>

<!-- footers -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/page_footer.php'; ?>

<script type="text/javascript">
    $(document).ready(function(){
        $('#password_view_control').hover(function () {
            $('#password').attr('type', 'text');
            $('#confirm').attr('type', 'text');
        }, function () {
            $('#password').attr('type', 'password');
            $('#confirm').attr('type', 'password');
        });
    });
</script>

<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
