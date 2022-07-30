<?php
/*
UserSpice 5
An Open Source PHP User Management System
by the UserSpice Team at http://UserSpice.com

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
//echo "helpers included";


$lang = [];
if (file_exists($abs_us_root.$us_url_root.'usersc/includes/custom_functions.php')) {
  require_once $abs_us_root.$us_url_root.'usersc/includes/custom_functions.php';
}

$usplugins = parse_ini_file($abs_us_root.$us_url_root.'usersc/plugins/plugins.ini.php', true);
foreach ($usplugins as $k => $v) {
  if ($v == 1) {
    if (file_exists($abs_us_root.$us_url_root.'usersc/plugins/'.$k.'/override.php')) {
      include $abs_us_root.$us_url_root.'usersc/plugins/'.$k.'/override.php';
    }
  }
}

require_once $abs_us_root.$us_url_root.'users/helpers/us_helpers.php';
require_once $abs_us_root.$us_url_root.'users/helpers/backup_util.php';
require_once $abs_us_root.$us_url_root.'users/helpers/class.treeManager.php';
require_once $abs_us_root.$us_url_root.'users/helpers/menus.php';
require_once $abs_us_root.$us_url_root.'users/helpers/permissions.php';
require_once $abs_us_root.$us_url_root.'users/helpers/users.php';
require_once $abs_us_root.$us_url_root.'users/helpers/dbmenu.php';

define('ABS_US_ROOT', $abs_us_root);
define('US_URL_ROOT', $us_url_root);

if (file_exists($abs_us_root.$us_url_root.'usersc/vendor/autoload.php')) {
  require_once $abs_us_root.$us_url_root.'usersc/vendor/autoload.php';
}

if (file_exists($abs_us_root.$us_url_root.'users/vendor/autoload.php')) {
  require_once $abs_us_root.$us_url_root.'users/vendor/autoload.php';
}



require $abs_us_root.$us_url_root.'users/classes/phpmailer/PHPMailerAutoload.php';
use PHPMailer\PHPMailer\PHPMailer;

require_once $abs_us_root.$us_url_root.'users/includes/user_spice_ver.php';

// Readeable file size
if (!function_exists('size')) {
  function size($path)
  {
    $bytes = sprintf('%u', filesize($path));

    if ($bytes > 0) {
      $unit = intval(log($bytes, 1024));
      $units = ['B', 'KB', 'MB', 'GB'];

      if (array_key_exists($unit, $units) === true) {
        return sprintf('%d %s', $bytes / pow(1024, $unit), $units[$unit]);
      }
    }

    return $bytes;
  }
}

//Pass through to static method in input class
if (!function_exists('sanitize')) {
  function sanitize($string)
  {
    return Input::sanitize($string);
  }
}

//returns the name of the current page
if (!function_exists('currentPage')) {
  function currentPage()
  {
    $uri = $_SERVER['PHP_SELF'];
    $path = explode('/', $uri);
    $currentPage = end($path);

    return $currentPage;
  }
}

if (!function_exists('currentFolder')) {
  function currentFolder()
  {
    $uri = $_SERVER['PHP_SELF'];
    $path = explode('/', $uri);
    $currentFolder = $path[count($path) - 2];

    return $currentFolder;
  }
}

if (!function_exists('format_date')) {
  function format_date($date, $tz)
  {
    //return date("m/d/Y ~ h:iA", strtotime($date));
    $format = 'Y-m-d H:i:s';
    $dt = DateTime::createFromFormat($format, $date);
    // $dt->setTimezone(new DateTimeZone($tz));
    return $dt->format('m/d/y ~ h:iA');
  }
}

if (!function_exists('abbrev_date')) {
  function abrev_date($date, $tz)
  {
    $format = 'Y-m-d H:i:s';
    $dt = DateTime::createFromFormat($format, $date);
    // $dt->setTimezone(new DateTimeZone($tz));
    return $dt->format('M d,Y');
  }
}

if (!function_exists('money')) {
  function money($ugly)
  {
    return '$'.number_format($ugly, 2, '.', ',');
  }
}

//updated in 5.3.0 to now use the built in system messages feature
if (!function_exists('display_errors')) {
    function display_errors($errors = []){
      foreach($errors as $k=>$v){
        if(array_key_exists($errors[$k][1],$errors)){
          unset($errors[$k][1]);
        }
      } sessionValMessages($errors);
    }
}

if (!function_exists('display_successes')) {
  function display_successes($successes = [])
  {
    foreach($successes as $k=>$v){
      if(array_key_exists($successes[$k][1],$successes)){
        unset($successes[$k][1]);
      }
    }
    sessionValMessages([],$successes);
  }
}

if (!function_exists('email')) {
  function email($to, $subject, $body, $opts = [], $attachment = null)
  {
   global $abs_us_root,$us_url_root;
    /*you can now pass in
    $opts = array(
    'email' => 'from_email@aol.com',
    'name'  => 'Bob Smith',
    'cc'    => 'cc@example.com',
    'bcc'   => 'bcc@example.com'
  );
  */
  $db = DB::getInstance();
  $query = $db->query('SELECT * FROM email');
  $results = $query->first();

  $mail = new PHPMailer();
  $mail->CharSet = 'UTF-8';
  $mail->SMTPDebug = $results->debug_level;               // Enable verbose debug output
  if ($results->isSMTP == 1) {
    $mail->isSMTP();
  }             // Set mailer to use SMTP
  $mail->Host = $results->smtp_server;  									// Specify SMTP server
  $mail->SMTPAuth = $results->useSMTPauth;                // Enable SMTP authentication
  $mail->Username = $results->email_login;                 // SMTP username
  $mail->Password = html_entity_decode($results->email_pass);    // SMTP password
  $mail->SMTPSecure = $results->transport;                 // Enable TLS encryption, `ssl` also accepted
  $mail->Port = $results->smtp_port;                       // TCP port to connect to

  if($attachment != false){
            $mail->addAttachment($attachment);
          }

          if(isset($opts['email']) && isset($opts['name'])){
            $mail->setFrom($opts['email'], $opts['name']);
          }else{
            $mail->setFrom($results->from_email, $results->from_name);
          }

          if(isset($opts['cc'])){
            $mail->addCC($opts['cc']);
          }

          if(isset($opts['bcc'])){
            $mail->addBCC($opts['bcc']);
          }

  	$mail->addAddress(rawurldecode($to));
    if($results->isHTML == 'true'){
      $mail->isHTML(true);
    }

  	$mail->Subject = $subject;
  	$mail->Body    = $body;
    if (!empty($attachment)) $mail->addAttachment($attachment);
    if(file_exists($abs_us_root.$us_url_root."usersc/scripts/email_function_override.php")){
      include $abs_us_root.$us_url_root."usersc/scripts/email_function_override.php";
    }
  	$result = $mail->send();

  	return $result;
  }
  }

if (!function_exists('email_body')) {
  function email_body($template, $options = [])
  {
    global $abs_us_root, $us_url_root;
    extract($options);
    ob_start();
    if (file_exists($abs_us_root.$us_url_root.'usersc/views/'.$template)) {
      require $abs_us_root.$us_url_root.'usersc/views/'.$template;
    } elseif (file_exists($abs_us_root.$us_url_root.'users/views/'.$template)) {
      require $abs_us_root.$us_url_root.'users/views/'.$template;
    }

    return ob_get_clean();
  }
}

//preformatted var_dump function
if (!function_exists('dump')) {
  function dump($var, $adminOnly = false, $localhostOnly = false)
  {
    if ($adminOnly && isAdmin() && !$localhostOnly) {
      echo '<pre>';
      var_dump($var);
      echo '</pre>';
    }
    if ($localhostOnly && isLocalhost() && !$adminOnly) {
      echo '<pre>';
      var_dump($var);
      echo '</pre>';
    }
    if ($localhostOnly && isLocalhost() && $adminOnly && isAdmin()) {
      echo '<pre>';
      var_dump($var);
      echo '</pre>';
    }
    if (!$localhostOnly && !$adminOnly) {
      echo '<pre>';
      var_dump($var);
      echo '</pre>';
    }
  }
}

//preformatted dump and die function
if (!function_exists('dnd')) {
  function dnd($var, $adminOnly = false, $localhostOnly = false)
  {
    dump($var, $adminOnly, $localhostOnly);
    die();
  }
}

if (!function_exists('bold')) {
  function bold($text)
  {
    echo "<text padding='1em' align='center'><h4><span style='background:white'>";
    echo $text;
    echo '</h4></text>';
  }
}

if (!function_exists('err')) {
  function err($text)
  {
    // echo "<text padding='1em' align='center'><span style='color:red'><h4><span class='errSpan'>";
    // echo $text;
    // echo '</span></h4></span></text>';
  }
}

if (!function_exists('redirect')) {
  function redirect($location)
  {
    header("Location: {$location}");
  }
}

//PLUGIN Stuff
foreach ($usplugins as $k => $v) {
  if ($v == 1) {
    if (file_exists($abs_us_root.$us_url_root.'usersc/plugins/'.$k.'/functions.php')) {
      include $abs_us_root.$us_url_root.'usersc/plugins/'.$k.'/functions.php';
    }
  }
}

if (!function_exists('write_ini_file')) {
  function write_php_ini($array, $file)
  {
    $res = [];
    foreach ($array as $key => $val) {
      if (is_array($val)) {
        $res[] = "[$key]";
        foreach ($val as $skey => $sval) {
          $res[] = "$skey = ".(is_numeric($sval) ? $sval : '"'.$sval.'"');
        }
      } else {
        $res[] = "$key = ".(is_numeric($val) ? $val : '"'.$val.'"');
      }
    }
    safefilerewrite($file, implode("\r\n", $res));
  }
}

if (!function_exists('safefilerewrite')) {
  function safefilerewrite($fileName, $dataToSave)
  {
    $security = ';<?php die();?>';

    if ($fp = fopen($fileName, 'w')) {
      $startTime = microtime(true);
      do {
        $canWrite = flock($fp, LOCK_EX);
        // If lock not obtained sleep for 0 - 100 milliseconds, to avoid collision and CPU load
        if (!$canWrite) {
          usleep(round(rand(0, 100) * 1000));
        }
      } while ((!$canWrite) and ((microtime(true) - $startTime) < 5));

      //file was locked so now we can store information
      if ($canWrite) {
        fwrite($fp, $security.PHP_EOL.$dataToSave);
        flock($fp, LOCK_UN);
      }
      fclose($fp);
    }
  }
}


/////////////////////////////////////////////////////////////////////////////////////////

require('db.php');
global $con;
//detects and assigns short forms for thousands,millions,billions etc
if (!function_exists('numberShortner')) {
    function numberShortener($number)
    {
        $value = "";
        if ($number < 999) {
            $value = $number;
        } elseif ((1000000 > $number) && ($number > 999)) {
            $value = (number_format(($number / 1000), 1)) . "K";
        } elseif (($number >= 1000000) && ($number < 1000000000)) {
            $value = (number_format(($number / 1000000), 1)) . "M";
        } elseif ($number >= 1000000000) {
            $value = (number_format(($number / 1000000000), 1)) . "B";
        }

        return $value;
    }
}
//calculates and returns time ago for the timeline
if (!function_exists('timelineDate')) {
    function timelineDate($date, $location)
    {
        $diff = time() - strtotime($date);
        $days = floor($diff / (60 * 60 * 24));
        $remainder = $diff % (60 * 60 * 24);
        $hours = floor($remainder / (60 * 60));
        $remainder = $remainder % (60 * 60);
        $minutes = floor($remainder / 60);
        $seconds = $remainder % 60;

        $dt = new DateTime($date);
        $time = $dt->format('H:i');
        if ($location == 1) {
            if (($days > 0)) {
                //$oldLocale = setlocale(LC_TIME, 'pt_BR');
                $date = strftime("%e %b %Y", strtotime($date));
                echo "<span class='date'>" . $date . "</span>";
                echo "<span class='time'>$time</span>";
                // setlocale(LC_TIME, $oldLocale);
            } elseif ($days == 0 && $hours == 0 && $minutes == 0) {
                echo "<span class='date'>today</span>";
                echo "<span class='time'>few seconds ago</span>";
            } elseif ($hours > 0) {
                echo "<span class='date'>today</span>";
                echo "<span class='time'>$hours  hours ago</span>";
            } elseif ($days == 0 && $hours == 0) {
                echo "<span class='date'>today</span>";
                echo "<span class='time'>minutes ago</span>";
            } else {
                echo "<span class='date'>today</span>";
                echo "<span class='time'>few seconds ago</span>";
            }
        } else {
            if (($days > 0)) {
                //$oldLocale = setlocale(LC_TIME, 'pt_BR');
                $date = strftime("%e %b %Y", strtotime($date));
                echo "<span class='date'>" . $days . " days ago</span>";
                // setlocale(LC_TIME, $oldLocale);
            } elseif ($days == 0 && $hours == 0 && $minutes == 0) {
                echo "<span class='time'>few seconds ago</span>";
            } elseif ($hours) {
                echo "<span class='time'>$hours  hours ago</span>";
            } elseif ($days == 0 && $hours == 0) {
                echo "<span class='time'>minutes ago</span>";
            } else {
                echo "<span class='time'>few seconds ago</span>";
            }
        }
    }
}

$query = "SELECT DISTINCT posts.p_id,posts.userid,posts.type,posts.sector,posts.title,posts.url,posts.description,
                            posts.cur_image,posts.likes,posts.post_type,posts.posted_by,posts.post,posts.views,users.*,
                            posts.date_created FROM posts,users
                            where posts.userid=1 and posts.posted_by=1 and users.id =posts.userid
                            order by posts.p_id desc";
$result = $con->query($query);

$breach = "SELECT COUNT(*) AS Total, type FROM posts WHERE type=1";//Possible breaches
$pbreach = $con->query($breach);
$pbre = $pbreach->fetch_array(MYSQLI_ASSOC);

$breach_diver = "SELECT COUNT(*) AS Total, type FROM posts WHERE type=0";//Possible breaches
$dbreach = $con->query($breach_diver);
$dbre = $dbreach->fetch_array(MYSQLI_ASSOC);

$outBreak = "SELECT COUNT(*) AS Total, type FROM posts WHERE type=2";//Disease outbreak
$oB = $con->query($outBreak);
$outB = $oB->fetch_array(MYSQLI_ASSOC);

$poacher = "SELECT COUNT(*) AS Total, type FROM posts WHERE type=3";//Apprehended poacher
$po = $con->query($poacher);
$poac = $po->fetch_array(MYSQLI_ASSOC);

$po = "SELECT COUNT(*) AS resolved, resolved_by FROM posts WHERE resolved_by=1";//can be edited with more users
$pod = $con->query($po);
$pou = $pod->fetch_array(MYSQLI_ASSOC);

$po1 = "SELECT COUNT(*) AS posted, posted_by FROM posts WHERE posted_by=1";//can be edited with more users
$pod1 = $con->query($po1);
$pou1 = $pod1->fetch_array(MYSQLI_ASSOC);

$total_pounts = $pou['resolved'] + $pou1['posted'];
// Set logged in user id: This is just a simulation of user login. We haven't implemented user log in
// But we will assume that when a user logs in,
// they are assigned an id in the session variable to identify them across pages
$user_id = 1;

// Receives a user id and returns the username
function getUsernameById($id)
{
    global $con;
    $result = mysqli_query($con, "SELECT fullname FROM users WHERE id=" . $id . " LIMIT 1");
    // return the username
    return mysqli_fetch_assoc($result)['fullname'];
}

// Receives a comment id and returns the fullname
function getRepliesByCommentId($id)
{
    global $con;
    $result_query = "SELECT * FROM replies WHERE comment_id=$id";
    $result = $con->query($result_query);
    return $result;
}

// Receives a post id and returns the total number of comments on that post
function getCommentsCountByPostId($post_id)
{
    global $con;
    $result = mysqli_query($con, "SELECT COUNT(*) AS total FROM comments");
    $data = mysqli_fetch_assoc($result);
    return $data['total'];
}

// If the user clicked submit on comment form...
if (isset($_POST['comment_posted'])) {
    global $con;
    // grab the comment that was submitted through Ajax call
    $comment_text = $_POST['comment_text'];
    // insert comment into database
    $sql = "INSERT INTO comments (post_id, user_id, comment, created_on, updated_on) VALUES (1, " . $user_id . ", '$comment_text', now(), null)";
    $result = mysqli_query($con, $sql);
    // Query same comment from database to send back to be displayed
    $inserted_id = $con->insert_id;
    $res = mysqli_query($con, "SELECT * FROM comments WHERE id=$inserted_id");
    $inserted_comment = mysqli_fetch_assoc($res);
    // if insert was successful, get that same comment from the database and return it
    if ($result) {

        $comment = "<div class='comment clearfix'>
					<div class='user'><img src='https://bootdey.com/img/Content/avatar/avatar6.png'></div>
					<div class='comment-details'>
						<span class='comment-name'>" . getUsernameById($inserted_comment['user_id']) . "</span>
						<span class='comment-date'>" . date('F j, Y ', strtotime($inserted_comment['created_on'])) . "</span>
						<p>" . $inserted_comment['comment'] . "</p>
						<a class='reply-btn' href='#' data-id='" . $inserted_comment['id'] . "'>reply</a>
					</div>
					<!-- reply form -->
					<form action='dashboard.php' class='reply_form clearfix' id='comment_reply_form_" . $inserted_comment['id'] . "' data-id='" . $inserted_comment['id'] . "'>
						<textarea class='form-control' name='reply_text' id='reply_text' cols='30' rows='2'></textarea>
						<button class='btn btn-primary btn-xs pull-right submit-reply'>Submit reply</button>
					</form>
				</div>";
        $comment_info = array(
            'comment' => $comment,
            'comments_count' => getCommentsCountByPostId(1)
        );
        echo json_encode($comment_info);
        exit();
    } else {
        echo "error";
        exit();
    }
}
// If the user clicked submit on reply form...
if (isset($_POST['reply_posted'])) {
    global $con;
    // grab the reply that was submitted through Ajax call
    $reply_text = $_POST['reply_text'];
    $comment_id = $_POST['comment_id'];
    // insert reply into database
    $sql = "INSERT INTO replies (user_id, comment_id, reply, created_at, updated_at) VALUES (" . $user_id . ", $comment_id, '$reply_text', now(), null)";
    $result = mysqli_query($con, $sql);
    $inserted_id = $con->insert_id;
    $res = mysqli_query($con, "SELECT * FROM replies WHERE id=$inserted_id");
    $inserted_reply = mysqli_fetch_assoc($res);
    // if insert was successful, get that same reply from the database and return it
    if ($result) {
        $reply = "<div class='comment reply clearfix'>
					<div class='user'><img src='https://bootdey.com/img/Content/avatar/avatar6.png'></div>
					<div class='comment-details'>
						<span class='comment-name'>" . getUsernameById($inserted_reply['user_id']) . "</span>
						<span class='comment-date'>" . date('F j, Y ', strtotime($inserted_reply['created_at'])) . "</span>
						<p>" . $inserted_reply['reply'] . "</p>
						<a class='reply-btn' href='#'>reply</a>
					</div>
				</div>";
        echo $reply;
        exit();
    } else {
        echo "error";
        exit();
    }
}




