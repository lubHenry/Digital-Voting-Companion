<?php
global $abs_us_root,$us_url_root,$hooks,$settings,$dest,$token,$result,$con;
if(file_exists("install/index.php")){
	//perform redirect if installer files exist
	//this if{} block may be deleted once installed
	header("Location: install/index.php");
}

require_once 'users/init.php';
require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
if(isset($user) && $user->isLoggedIn()){
}
?>

    <?php
        if($user->isLoggedIn()){?>
           <!-- <div class="sidebar" data-color="white" data-active-color="danger">
                <div class="logo">
                    <a href="" class="simple-text logo-mini">
                        <div class="logo-image-small">
                            <img src="<?=$us_url_root?>users/images/logo2.png">
                        </div>
                        <!-- <p>CT</p> --><!--
                    </a>
                    <a href="" class="simple-text logo-normal">
                        <?php echo $settings->site_name;?>
                    </a>
                </div>
                <div class="sidebar-wrapper">
                    <ul>
                        <li >
                            <a href="#">
                                <span >Components</span>
                                <span >&#x1F525;</span>
                            </a>
                            <div>
                                <ul>
                                    <li>
                                        <a href="#">
                                            <span >Grid</span>
                                        </a>
                                    </li>
                                    <li >
                                        <a href="#">
                                            <span >Layout</span>
                                        </a>
                                    </li>
                                    <li >
                                        <a href="#">
                                            <span >Forms</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li >
                            <a href="#">
                                <span>E-commerce</span>
                            </a>
                            <div class=>
                                <ul>
                                    <li >
                                        <a href="#">
                                            <span >Products</span>
                                        </a>
                                    </li>
                                    <li >
                                        <a href="#">
                                            <span >Orders</span>
                                        </a>
                                    </li>
                                    <li >
                                        <a href="#">
                                            <span >credit card</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>-->
		<div class="jumbotron">
			<h1 align="center"><?=lang("JOIN_SUC");?> <?php echo $settings->site_name;?></h1>
            <?php
            while ($row = $result->fetch_assoc()) {
            // Get all comments from database
            $comments_query = "SELECT * FROM comments WHERE post_id=" . $row['p_id'] . " ORDER BY created_on DESC";
            $comments_query_result = $con->query($comments_query);
            ?>
            <ul class="timeline">
                <li id="" onload="">
                    <!-- begin timeline-time -->
                    <div class="timeline-time">
                        <?php
                        timelineDate($row['date_created'],1);
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
                        <div class="timeline-header">
                            <span class="userimage"><img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt=""></span>
                            <span class="username"><a href="javascript:;"><?=$row['fullname'];?></a> <small></small></span>
                            <!--<span class="pull-right text-muted"><a href=""><b>Respond</b></a></span>-->
                        </div>
                        <div class="timeline-content">
                            <?php if($row['type']!=1){ ?>
                                <p class="lead">
                                    <i class="fa fa-quote-left fa-fw pull-left"></i>
                                    <?=$row['post'];?>
                                    <i class="fa fa-quote-right fa-fw pull-right"></i>
                                </p>
                            <?php }else{?>
                                <h4 class="template-title">
                                    <i class="fa fa-map-marker text-danger fa-fw"></i>
                                    Sector <?=$row['sector'];?>
                                </h4>
                                <p><?=$row['post'];?></p>
                                <p class="m-t-20">
                                    <img src="<?=$us_url_root?>usersc/templates/<?=$settings->template?>/assets/images/hippop.jpeg">
                                </p>
                            <?php }?>
                        </div>
                        <div class="timeline-likes">
                            <div class="stats-right">
                                <a href=""><span class="fa-stack fa-fw stats-icon" >
                               <i class="fa fa-circle fa-stack-2x text-primary"></i>
                               <i class="fa fa-comments fa-stack-1x fa-inverse t-plus-1"></i>
                               </span>
                                    <span class="stats-text" >42</span> </a>&nbsp;&nbsp;&nbsp;&nbsp;
                            </div>
                            <div class="stats" id="">
                               <span class="fa-stack fa-fw stats-icon" >
                               <i class="fa fa-circle fa-stack-2x text-primary"></i>
                               <i class="fa fa-thumbs-up fa-stack-1x fa-inverse t-plus-1"></i>
                               </span>
                                <span class="stats-total" ><?=$row['likes'];?> Members Approve </span>
                            </div>
                        </div>
                        <div class="timeline-footer">
                            <a href="javascript:;" class="m-r-15 text-inverse-lighter"><i class="fa fa-thumbs-up fa-fw fa-lg m-r-3"></i> Approve</a>
                            <a href="javascript:;"  class="toggle-btn m-r-15 text-inverse-lighter"  value="<?php echo $row['p_id'];?>"><i class="fa fa-thumbs-down fa-fw fa-lg m-r-3"></i> Object</a>
                            <button type="button" class="btn btn-sm pull-right toggle-btn" id="<?php echo $row['p_id'];?>" value="<?php echo $row['p_id'];?>">Show/Hide</button>
                        </div>
                        <div class="timeline-comment-box" id="display">
                            <!--<div class="user"><img src="https://bootdey.com/img/Content/avatar/avatar6.png"></div>
                            <div class="input">
                               <form class="clearfix" action="dashboard.php" method="post" id="comment_form">
                                  <div class="input-group">
                                      <textarea name="comment_text" id="comment_text" class="form-control" cols="30" rows="1"></textarea>
                                     <span class="input-group-btn p-l-10">
                                     <button class="btn btn-primary btn-sm pull-right" id="submit_comment">Comment</button>
                                     </span>
                                  </div>
                               </form>
                            </div>-->
                            <hr/>
                            <?php if (isset($comments_query_result)): ?>
                                <?php while ($c_row = $comments_query_result->fetch_assoc()){?>
                                    <ul  class="timeline">
                                        <!-- comments wrapper -->
                                        <div id="comments-wrapper"><!--listed-comment_<?php //echo $c_row['user_id'];?>_<?php //echo $c_row['id'];?>-->
                                            <?php
                                            //fetch comment id and user id from db using php
                                            $comment_id = $c_row['post_id'];
                                            $c_usr_id = $c_row['user_id'];
                                            $comnt_user_id = $comment_id."_".$c_usr_id;//combine to make each comment unique
                                            ?>
                                            <!-- comment -->
                                            <li id="<?php echo $comment_id;?>" class="slow<?php echo $comment_id;?>" style="display:none" value="<?php echo $c_row['post_id '];?>">
                                                <div class="timeline-time">
                                                    <?php timelineDate($c_row['created_on'],1);?>
                                                </div>
                                                <div class="timeline-icon">
                                                    <a href="javascript:;">&nbsp;</a>
                                                </div>
                                                <div class="timeline-body">
                                                    <di01v class="timeline-header">
                                                        <div class="user"><img src="https://bootdey.com/img/Content/avatar/avatar6.png"></div>
                                                        <div class="comment-details">
                                                            <span class="comment-name"><?php echo getUsernameById($c_row['user_id']) ?></span>
                                                        </div>
                                                    </di01v>
                                                    <div class="timeline-content">
                                                        <p class="lead">
                                                            <i class="fa fa-quote-left fa-fw pull-left"></i>
                                                            <?php echo $c_row['comment']; ?>
                                                            <i class="fa fa-quote-right fa-fw pull-right"></i>
                                                        </p>
                                                    </div>
                                                    <div class="timeline-likes">
                                                        <div class="stats-right">
                                                            <a href="">
                                                       <span class="fa-stack fa-fw stats-icon" >
                                                           <i class="fa fa-circle fa-stack-2x text-primary"></i>
                                                           <i class="fa fa-comments fa-stack-1x fa-inverse t-plus-1"></i>
                                                       </span></a>
                                                            <span class="stats-text" >42</span> &nbsp;&nbsp;&nbsp;&nbsp;
                                                        </div>
                                                        <div class="stats" id="">
                                                            <input type="text" hidden/>
                                                            <span class="fa-stack fa-fw stats-icon" >
                                                       <i class="fa fa-circle fa-stack-2x text-primary"></i>
                                                       <i class="fa fa-thumbs-up fa-stack-1x fa-inverse t-plus-1"></i>
                                                   </span>
                                                            <span class="stats-total" ><?=$row['likes'];?>  Approve </span>

                                                            <span class="fa-fw stats-icon" >
                                                       <i class="fa fa-circle fa-stack-2x text-primary"></i>
                                                       <i class="fa fa-thumbs-down fa-stack-1x fa-inverse t-plus-1"></i>
                                                   </span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                            <span class="stats-total" ><?=$row['likes'];?>  Object</span>
                                                        </div>
                                                    </div>
                                                    <div class="timeline-footer">
                                                        <a class="reply-btn" href="#" data-id="<?=$c_row['id']; ?>">approve</a> . <a class="like-btn" href="#">object</a>
                                                    </div>
                                                    <div class="comment clearfix">
                                                        <!-- reply form -->
                                                        <div class="input">
                                                            <!--<form action="dashboard.php" class="reply_form clearfix" id="comment_reply_form_<?=$c_row['id'];?>" data-id="<?=$c_row['id'];?>">
                                           <textarea class="form-control" name="reply_text" id="reply_text" cols="30" rows="1"></textarea>
                                           <button class="btn btn-primary btn-xs pull-right submit-reply">Reply</button>
                                       </form>-->
                                                            <?php $replies = getRepliesByCommentId($c_row['id']) ?>
                                                            <div class="replies_wrapper_<?php echo $c_row['id']; ?>">
                                                                <?php if (isset($replies)): ?>
                                                                    <hr/>
                                                                    <?php while ($reply = $replies->fetch_assoc()){?>
                                                                        <!-- reply -->
                                                                        <div class="comment reply clearfix">
                                                                            <div class="user"><img src="https://bootdey.com/img/Content/avatar/avatar6.png"></div>
                                                                            <div class="comment-details">
                                                                                <?php
                                                                                $comment_user_qry = "SELECT user_id from comments where id = ".$reply['comment_id'];
                                                                                $comment_user = $con->query($comment_user_qry);
                                                                                $name= $comment_user->fetch_array(MYSQLI_ASSOC);
                                                                                ?>
                                                                                <span class="comment-name"><?php echo getUsernameById($reply['user_id']) ?></span>
                                                                                <p><b>@<?=getUsernameById($name['user_id']);?></b> <?=$reply['reply'];?></p>
                                                                            </div>
                                                                            <!--<div class="timeline-footer">
                                               <a class="reply-btn" href="#" data-id="<?php //echo $reply['id']; ?>">reply</a> . <a class="like-btn" href="#" data-id="<?php //echo $reply['id']; ?>">approve</a> . <a class="comment-date"><?php //timelineDate($reply["created_at"],0) ?></a>
                                           </div>-->
                                                                        </div>
                                                                    <?php } ?>
                                                                <?php endif ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                            </li>

                                        </div>
                                    </ul>
                                <?php }?>
                            <?php endif ?>
                        </div>
                    </div>
                    <!-- end timeline-body -->
                </li>
                <?php }?>
            </ul>
			<br>
		</div>
        <?php
            }else{
            Redirect::to($us_url_root.$settings->redirect_uri_after_sign_out);
        }?>

<?php  languageSwitcher();?>


<!-- Place any per-page javascript here -->
<?php require_once $abs_us_root . $us_url_root . 'users/includes/html_footer.php'; ?>
