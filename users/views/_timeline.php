<?php
global $logged_id,$userId,$db,$abs_us_root,$us_url_root;

if(!$logged_id)
$logged_id = Input::get('x');

$next_records = 5;
$show_more_button = 0;
$show_comments_per_page = 3;

$logged_user_pic ='';

$memberPic = getUserImg($userId);

if(Input::get('value'))
{
$userId = Input::get('x');
$posted_on = Input::get('p');
$val = checkValues(Input::get('value'));
$db->query("INSERT INTO posts (post,userid,date_created,posted_by) VALUES(?,?,?,?)",[$val,$posted_on,strtotime(date('Y-m-d H:i:s')),$userId]);

################
$lastID = $db->lastId();
############

$result = $db->query("SELECT DISTINCT posts.p_id,posts.userid,posts.type,posts.title,posts.url,posts.description,
posts.cur_image,posts.likes,posts.post_type,posts.posted_by,posts.post,posts.views,users.*,
UNIX_TIMESTAMP() - posts.date_created AS TimeSpent,posts.date_created FROM posts,users
where posts.userid=? and posts.posted_by=? and users.id =posts.userid
order by posts.p_id desc limit 1",[$posted_on,$userId])->first();

}
else if(Input::get('image_url'))
{
$userId = Input::get('x');
$posted_on = Input::get('p');

$image_url = checkValues(Input::get('image_url'));
$post = checkValues(Input::get('post'));
$uip = $_SERVER['REMOTE_ADDR'];
$db->query("INSERT INTO posts (post,userid,date_created,posted_by,uip,cur_image,post_type) VALUES(?,?,?,?,?,?,?)",[$post,$posted_on,strtotime(date('Y-m-d H:i:s')),$userId,$uip,$image_url,'2']); // 2 = image only

$result = $db->query("SELECT DISTINCT posts.p_id,posts.uip,posts.userid,posts.post_type,posts.title,posts.url,
posts.likes,posts.description,posts.cur_image,posts.type,posts.posted_by,posts.post,posts.views,
users.*, UNIX_TIMESTAMP() - posts.date_created AS TimeSpent,
posts.date_created FROM posts,users where posts.userid=".$posted_on." and posts.posted_by=".$userId."
and users.id =posts.userid order by posts.p_id desc limit 1 ")->first();
}
elseif(Input::get('show_more_post')) // more posting paging
{
$next_records = Input::get('show_more_post') + 10;
$posted_on = Input::get('p');

$result = $db->query("SELECT DISTINCT posts.p_id,posts.uip,posts.userid,posts.post_type,posts.title,posts.url,
posts.likes,posts.description,posts.cur_image,posts.type,posts.posted_by,posts.post,posts.views,
users.*, UNIX_TIMESTAMP() - posts.date_created AS TimeSpent,posts.likes,
posts.date_created FROM posts,users where users.id = ".$posted_on." and users.id =posts.userid
order by posts.p_id desc limit ".Input::get('show_more_post').", 10")->results();
$check_res = $db->query("SELECT DISTINCT posts.p_id FROM posts,users where users.id = ".$posted_on." and users.id =posts.userid
order by posts.p_id desc limit ".$next_records.", 10");
$show_more_button = 0; // button in the end
$check_result = $check_res->count();
if($check_result > 0)
{
$show_more_button = 1;
}
}
else
{
$show_more_button = 1;

$result = $db->query("SELECT DISTINCT posts.p_id,posts.uip,posts.userid,posts.title,posts.url,posts.likes,
posts.cur_image,posts.post_type,posts.type,posts.posted_by,posts.post,posts.title,
posts.url,posts.description,posts.views,users.*, UNIX_TIMESTAMP() - posts.date_created AS TimeSpent,
posts.date_created FROM posts,users where users.id =".$userId." and users.id = posts.userid
order by posts.p_id desc limit 0,5")->results();
}

?>
<ul class="timeline">
<?php
foreach ($result as $row)
{
    //checking if member liked a given post
    $flag_liked = 0;
    $nResult = $db->query("SELECT * FROM likes_track WHERE member_id=".$userId." AND post_id=".$row->p_id." AND liked=1");
if ($nResult->count()>0)
{
    $flag_liked = 1;
}

$comments = $db->query("SELECT t1.*,t2.*,t1.created_on AS CommentTimeSpent FROM comments_track as t1,
users as t2 where t1.post_id =".$row->p_id." AND t1.member_id=t2.id
order by t1.c_id desc limit 0,$show_comments_per_page")->results();

$comments_counts = $db->query("SELECT t1.*,t2.fname,t2.id,t2.lname FROM comments_track as t1,
users as t2 where t1.post_id = ".$row->p_id." AND t1.member_id=t2.id order by t1.c_id desc");

$number_of_comments = $comments_counts->count();

if($number_of_comments>0)
{
$m=1; $friends_list = array();
foreach ($comments_counts as $rows)
{
if( $m < $show_comments_per_page-1 ){ $m++; continue; }
else
{
$full_name = $rows['fname'].' '.$rows['lname'];
if( !in_array($full_name,$friends_list) )
array_push($friends_list, $full_name );  $m++;
}
if($m-$show_comments_per_page > 8)
break;
}

$namestring = implode(', ', $friends_list);
}

?>
    <li id="post_id<?=$row->p_id;?>" onload="javascript: updateViews(<?=$row->p_id;?>,<?=$userId;?>,<?=$row->posted_by;?>)">
        <div class="timeline-time">
        <!-- begin timeline-time -->
        <?php
        $diff = time() - strtotime($row->date_created);
        $days = floor($diff / (60 * 60 * 24));
        $remainder = $diff % (60 * 60 * 24);
        $hours = floor($remainder / (60 * 60));
        $remainder = $remainder % (60 * 60);
        $minutes = floor($remainder / 60);
        $seconds = $remainder % 60;

        $dt = new DateTime($row->date_created);
        $time = $dt->format('H:i');

        if($days > 0) {
            //$oldLocale = setlocale(LC_TIME, 'pt_BR');
            $row->date_created = strftime("%e %b %Y", strtotime($row->date_created));
            echo "<span class='date'>$row->date_created</span>";
            echo "<span class='time'>$time</span>";
            // setlocale(LC_TIME, $oldLocale);
        }
        elseif($days == 0 && $hours == 0 && $minutes == 0) {
            echo "<span class='date'>today</span>";
            echo "<span class='time'>few seconds ago</span>";
        }
        elseif($hours) {
            echo "<span class='date'>today</span>";
            echo "<span class='time'>$hours . ' hours ago'</span>";
        }
        elseif($days == 0 && $hours == 0) {
            echo "<span class='date'>today</span>";
            echo "<span class='time'>minutes ago</span>";
        }
        else {
            echo "<span class='date'>today</span>";
            echo "<span class='time'>few seconds ago</span>";
        }
            ?>
        </div>
        <!-- end timeline-time -->
        <!-- begin timeline-icon -->
        <div class="timeline-icon">
            <a href="javascript:;">&nbsp;</a>
        </div>
        <!-- end timeline-icon -->
        <!-- begin timeline-body -->
        <div class="timeline-body">
            <?php
            if($row->id == $row->posted_by)
            {
                $memberPic = getUserImg($row->posted_by);?>
                <div class="timeline-header">
                    <span class="userimage"><img src="users/<?=$memberPic;?>" alt=""></span>
                    <span class="username"><a href="javascript:;"><?php echo $row->fname.' '.$row->lname;?></a> <small></small></span>
                    <span class="pull-right text-muted" id="view-stats-<?=$row->p_id;?>"> <?php echo numberShortener(($row->p_id) ? $row->views : 0);?> Views</span>
                </div>
           <?php }
                else
                {
                $username_gets = $db->query("SELECT * from users where id=".$row->posted_by." order by id desc limit 1")->results();
                foreach ($username_gets as $names)
                {
                    $user_avatar_2 = $names->picture;
                    $user_id_2 = $names->id;
                    $fname_2 = $names->fname.' '. $names->lname;
                }

                $s_memberPic = getUserImg( $row->posted_by);?>

                    <div class="timeline-header">
                        <span class="userimage"><img src="<?=$s_memberPic;?>" alt=""></span>
                        <span class="username"><a href="javascript:;"><?php echo $fname_2;?></a> <small></small></span>
                        <span class="pull-right text-muted" id="view-stats-<?=$row->p_id;?>"> <?php echo numberShortener(($row->p_id) ? $row->views : 0);?> Views</span>
                    </div>
           <?php }?>
            <div class="timeline-content">
                <p>
                    <?=$row->post;?>
                </p>
            </div>
                <div class="timeline-likes" >
                   <div class="stats-right">
                       <a href="">
                            <span class="fa-stack fa-fw stats-icon" >
                              <i class="fa fa-circle fa-stack-2x text-primary"></i>
                              <i class="fa fa-share-square-o fa-stack-1x fa-inverse t-plus-1"></i>
                            </span>
                            <span class="stats-text" >259</span>
                       </a>
                    </div>
                    <div class="stats-right">
                        <?php if($number_of_comments>0) {
                            echo '<a href=""><span class="fa-stack fa-fw stats-icon" >';
                            echo  '<i class="fa fa-circle fa-stack-2x text-primary"></i>';
                            echo '<i class="fa fa-comments fa-stack-1x fa-inverse t-plus-1"></i>';
                            echo '</span>';
                            echo '<span class="stats-text" >' . numberShortener($number_of_comments) . '</span> </a>&nbsp;&nbsp;&nbsp;&nbsp;';
                        }?>
                    </div>
                    <div class="stats" id="ppl_like_div_<?=$row->p_id?>">
                        <input type="text" hidden/>
                    <?php if($row->likes>0){
                           echo '<span class="fa-stack fa-fw stats-icon" >';
                              echo '<i class="fa fa-circle fa-stack-2x text-primary"></i>';
                              echo '<i class="fa fa-thumbs-up fa-stack-1x fa-inverse t-plus-1"></i>';
                          echo '</span>';
                            if($flag_liked==0){?>
                              <span class="stats-total" ><?=numberShortener($row->likes);?> Members Approve </span>
                         <?php }else{
                                    if($nResult->count()==$row->likes){?>
                                        <span class="stats-total">You approve </span>
                                        <?php }else{?>
                                        <span class="stats-total">You and <?=numberShortener($row->likes-1);?> other members Approve </span>
                        <?php } } }?>
                    </div>
                </div>
            <div class="timeline-footer">
                <span id="like-panel-<?=$row->p_id;?>">
                    <a onclick="like(event)" href="users/likes.php?type=post" class="m-r-15 text-inverse-lighter " id="<?=$row->p_id;?>_<?=$userId;?>"><i class="fa fa-thumbs-up fa-fw fa-lg m-r-3"></i>Approve</a>
                </span>&nbsp;&nbsp;&nbsp;&nbsp;
                <span><a href="javascript:;" data-toggle="" data-target="#" class="m-r-15 text-inverse-lighter"><i class="fa fa-comments fa-fw fa-lg m-r-3"></i> Comment</a></span>&nbsp;&nbsp;&nbsp;&nbsp;
                <span><a href="javascript:;" class=" text-inverse-lighter"><i class="fa fa-share-square-o fa-fw fa-lg m-r-3"></i> Share</a></span>
            </div>
            <div class="timeline-comment-box">
                <?php $Pic_ = getUserImg($userId);?>
                    <div class="user"><img src="users/<?=$Pic_;?>"></div>
                <div class="input">
                    <form class="clearfix" method="post" id="comment_form">
                        <div class="input-group">
                            <input type="text" name="postId" id="postId" value="<?=$row->p_id;?>" hidden/>
                            <textarea name="comment_text" id="comment_text" class="form-control" cols="30" rows="3"></textarea>
                            <input type="text" name="userId" id="userId" value="4" hidden/>
                            <span class="input-group-btn p-l-10">
                               <button class="btn btn-primary btn-sm pull-right" id="submit_comment">Submit comment</button>
                            </span>
                        </div>
                    </form>
                </div>
                <!-- comments wrapper -->
                    <div id="comments-wrapper">
                        <?php if (isset($com)): ?>
                            <!-- Display comments -->
                            <?php foreach ($com as $comnt): ?>
                                <!-- comment -->
                                <div class="comment clearfix">
                                    <img src="profile.png" alt="" class="profile_pic">
                                    <div class="comment-details">
                                        <span class="comment-name">UserName Here</span>
                                        <span class="comment-date"><?php echo date("F j, Y ", strtotime($comnt['created_at'])); ?></span>
                                        <p><?php echo $comnt['body']; ?></p>
                                        <a class="reply-btn" href="#" data-id="<?php echo $comnt['id']; ?>">reply</a>
                                    </div>
                                    <!-- reply form -->
                                    <form action="post_details.php" class="reply_form clearfix" id="comment_reply_form_<?php echo $comnt['id'];?>" data-id="<?php echo $comnt['id']; ?>">
                                        <textarea class="form-control" name="reply_text" id="reply_text" cols="30" rows="2"></textarea>
                                        <button class="btn btn-primary btn-xs pull-right submit-reply">Submit reply</button>
                                    </form>

                                    <!-- GET ALL REPLIES -->
                                    <?php $replies = getRepliesByCommentId($comnt['id']) ?>
                                    <div class="replies_wrapper_<?php echo $comnt['id']; ?>">
                                        <?php if (isset($replies)): ?>
                                            <?php foreach ($replies as $reply): ?>
                                                <!-- reply -->
                                                <div class="comment reply clearfix">
                                                    <img src="profile.png" alt="" class="profile_pic">
                                                    <div class="comment-details">
                                                        <span class="comment-name"><?php echo getUsernameById($reply['user_id']) ?></span>
                                                        <span class="comment-date"><?php echo date("F j, Y ", strtotime($reply["created_at"])); ?></span>
                                                        <p><?php echo $reply['body']; ?></p>
                                                        <a class="reply-btn" href="#">reply</a>
                                                    </div>
                                                </div>
                                            <?php endforeach ?>
                                        <?php endif ?>
                                    </div>
                                </div>
                                <!-- // comment -->
                            <?php endforeach ?>
                        <?php else: ?>
                            <h2>Be the first to comment on this post</h2>
                        <?php endif ?>
                    </div><!-- comments wrapper -->
            </div>
           <!-- <div class="timeline-comment-box" id="reply_comment" style="display: block;">
                    <div id="comment<//?=$row->p_id;?>">
                        <//?php foreach ($comments as $comment) {?>
                            <table class="table table-bordered" id="reply<//?=$comment->id;?>">
                                <tr>
                                    <//?php if($comment->id == $comment->member_id){
                                    $memberPic = getUserImg($comment->member_id);}?>
                                    <th><span class="user"><img src="users/<//?=$memberPic;?>" alt=""></span>
                                        <span class="username"><b><//?php echo $comment->fname.' '.$comment->lname;?></b><small></small></span></th>
                                </tr>
                                <tr>
                                    <td colspan="3"><//?=$comment->comment;?></td>
                                </tr>
                            </table>
                        <//?php }?>
                    </div>
                </*?php
                $reply_counts = $db->query("SELECT count(*) as tot FROM comments_track as t1 where t1.post_id = ".$row->p_id)->first();
                $number_of_replies = $reply_counts->tot;
                if($number_of_replies>3){
                ?>
                <h6 class="load-more" id="<//?=$comment->c_id;?>">Load More</h6>
                <//?php }?>
                <input type="hidden" id="row" value="0">
                <input type="hidden" id="all" value="<//?=$number_of_replies;?>">
            </div>-->
        <!-- end timeline-body -->
    </li>
    <li>
        <!-- begin timeline-time -->
        <div class="timeline-time">
            <span class="date">24 February 2014</span>
            <span class="time">08:17</span>
        </div>
        <!-- end timeline-time -->
        <!-- begin timeline-icon -->
        <div class="timeline-icon">
            <a href="javascript:;">&nbsp;</a>
        </div>
        <!-- end timeline-icon -->
        <!-- begin timeline-body -->
        <div class="timeline-body">
            <div class="timeline-header">
                <span class="userimage"><img src="users/<?=getUserImg($userId);?>" alt=""></span>
                <span class="username">Richard Leong</span>
                <span class="pull-right text-muted">1,282 Views</span>
            </div>
            <div class="timeline-content">
                <p class="lead">
                    <i class="fa fa-quote-left fa-fw pull-left"></i>
                    Quisque sed varius nisl. Nulla facilisi. Phasellus consequat sapien sit amet nibh molestie placerat. Donec nulla quam, ullamcorper ut velit vitae, lobortis condimentum magna. Suspendisse mollis in sem vel mollis.
                    <i class="fa fa-quote-right fa-fw pull-right"></i>
                </p>
            </div>
            <div class="timeline-footer">
                <a href="javascript:;" class="m-r-15 text-inverse-lighter"><i class="fa fa-thumbs-up fa-fw fa-lg m-r-3"></i> Like</a>
                <a href="javascript:;" class="m-r-15 text-inverse-lighter"><i class="fa fa-comments fa-fw fa-lg m-r-3"></i> Comment</a>
                <a href="javascript:;" class="m-r-15 text-inverse-lighter"><i class="fa fa-share fa-fw fa-lg m-r-3"></i> Share</a>
            </div>
        </div>
        <!-- end timeline-body -->
    </li>
    <li>
        <!-- begin timeline-time -->
        <div class="timeline-time">
            <span class="date">10 January 2014</span>
            <span class="time">20:43</span>
        </div>
        <!-- end timeline-time -->
        <!-- begin timeline-icon -->
        <div class="timeline-icon">
            <a href="javascript:;">&nbsp;</a>
        </div>
        <!-- end timeline-icon -->
        <!-- begin timeline-body -->
        <div class="timeline-body">
            <div class="timeline-header">
                <span class="userimage"><img src="users/<?=getUserImg($userId);?>" alt=""></span>
                <span class="username">Lelouch Wong</span>
                <span class="pull-right text-muted">1,021,282 Views</span>
            </div>
            <div class="timeline-content">
                <h4 class="template-title">
                    <i class="fa fa-map-marker text-danger fa-fw"></i>
                    795 Folsom Ave, Suite 600 San Francisco, CA 94107
                </h4>
                <p>In hac habitasse platea dictumst. Pellentesque bibendum id sem nec faucibus. Maecenas molestie, augue vel accumsan rutrum, massa mi rutrum odio, id luctus mauris nibh ut leo.</p>
                <p class="m-t-20">
                    <img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="">
                </p>
            </div>
            <div class="timeline-footer">
                <a href="likes.php?type=post&id=<?=$row->p_id;?>" class="m-r-15 text-inverse-lighter"><i class="fa fa-thumbs-up fa-fw fa-lg m-r-3"></i> Like</a>
                <a href="javascript:;" class="m-r-15 text-inverse-lighter"><i class="fa fa-comments fa-fw fa-lg m-r-3"></i> Comment</a>
                <a href="javascript:;" class="m-r-15 text-inverse-lighter"><i class="fa fa-share fa-fw fa-lg m-r-3"></i> Share</a>
            </div>
        </div>
        <!-- end timeline-body -->
    </li>

<?php }?>
    <?php $connect = mysqli_connect("localhost", "miny", "Poundsty87%%", "userSpicyOnly");
          $query = "SELECT * FROM comments_track ORDER BY c_id DESC limit 3";
          $result = mysqli_query($connect, $query);?>
        <li>
            <!-- begin timeline-icon -->
            <div class="timeline-icon">
                <a href="javascript:;">&nbsp;</a>
            </div>
            <!-- end timeline-icon -->
            <!-- begin timeline-body -->
            <div class="timeline-body">
                Loading...
            </div>
            <!-- begin timeline-body -->
        </li>
    </ul>

<script type="text/javascript">
        function ajax_send(data, element) {

            var ajax = new XMLHttpRequest();

            ajax.addEventListener('readystatechange', function () {
                if (ajax.readyState == 4 && ajax.status == 200) {
                    response(ajax.responseText, element);
                }
            });
            data = JSON.stringify(data);

            ajax.open("post", "users/likes.php?data=" + data, true)
            ajax.send();
        }

        function response(result, element) {
            if (result != "") {
                var obj = JSON.parse(result);
                if (typeof obj.action != 'undefined') {
                    if (obj.action == 'like') {
                        var approve_text = document.getElementById("ppl_like_div_" + obj.post_id);
                        approve_text.innerHTML = obj.info;
                    }
                }
            }

        }

        function like(e) {
            e.preventDefault();
            var id = e.target.id;
            var id_array = id.split("_");
            var post_id = id_array[0];
            var user_id = id_array[1];
            var link = e.target.href;
            var data = {};
            data.link = link;
            data.post_id = post_id;
            data.user_id = user_id;
            data.action = "like";
            ajax_send(data, e.target)
        }

        function post_comment(e) {
            e.preventDefault();
            var post_id = $('#postId').val();
            var user_id = $('#userId').val();
            var message = $('#reply_'+post_id).val();
            var link = e.target.href;
            var data = {};
            data.link = link;
            data.message = message;
            data.post_id = post_id;
            data.user_id = user_id;
            data.action = "comment";
            ajax_comment(data, e.target)
        }

        function ajax_comment(data, element) {

            var ajax = new XMLHttpRequest();

            ajax.addEventListener('readystatechange', function () {
                if (ajax.readyState == 4 && ajax.status == 200) {
                    $('#reply').val("");
                    comment_response(ajax.responseText, element);
                }
            });
            data = JSON.stringify(data);

            ajax.open("POST", "users/comment.php?data=" + data, true)
            ajax.send();
        }

        function comment_response(result, element) {
            if (result != "") {
                var obj = JSON.parse(result);
                if (typeof obj.action != 'undefined') {
                    if (obj.action == 'comment') {
                        //var approve_text = document.getElementById("ppl_like_div_"+obj.post_id);
                        //approve_text.innerHTML = obj.info;
                        alert(obj.records);
                    }
                }
            }

        }


</script>
