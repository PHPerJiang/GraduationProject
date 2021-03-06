<!DOCTYPE HTML>
<html>
<head>
	<title>MyArticles</title>
	<link href="<?php echo site_url('assets/css/style-feed.css')?>" rel='stylesheet' type='text/css' />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" type="image/x-icon" href="<?php echo site_url('assets/images/logo.png')?>" />
	<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800' rel='stylesheet' type='text/css'>
    <script src="<?php echo base_url('assets/js/jquery-3.1.0.min.js')?>"></script>
    <!--    加载百度的ueditor插件-->
    <script type="text/javascript">
        var $ = jQuery.noConflict();
        $(function() {
            $('#activator').click(function(){
                $('#box').animate({'top':'0px'},500);
            });
            $('#boxclose').click(function(){
                $('#box').animate({'top':'-700px'},500);
            });
        });
        $(document).ready(function(){
            //Hide (Collapse) the toggle containers on load
            $(".toggle_container").hide();
            //Switch the "Open" and "Close" state per click then slide up/down (depending on open/close state)
            $(".trigger").click(function(){
                $(this).toggleClass("active").next().slideToggle("slow");
                return false; //Prevent the browser jump to the link anchor
            });

        });
	</script>
	<!----//End-dropdown--->
</head>
<body>
<!---start-wrap---->
<!---start-header---->
<div class="header">
	<div class="wrap">
		<div class="logo">
			<a href="index.php"><img src="<?php echo site_url('assets/images/logo.png')?>" title="pinbal"/></a>
		</div>
		<div class="nav-icon">
			<a href="#" class="right_bt" id="activator"><span> </span> </a>
		</div>
		<div class="box" id="box">
			<div class="box_content">
				<div class="box_content_center">
					<div class="form_content">
						<div class="menu_box_list">
							<ul>
                                <li><a href="<?php echo site_url('feed/index')?>"><span style="color: red">HOT&nbsp;</span>热门信息</a></li>
                                <li><a href="<?php echo site_url('follow/follow_list')?>"><span>我关注的信息</span></a></li>
                                <li><a href="<?php echo site_url('follow/follow_user_list')?>"><span>我关注的用户</span></a></li>
                                <li><a href="<?php echo site_url('article_list/index')?>"><span>我发布的信息</span></a></li>
                                <div class="clear"> </div>
							</ul>
						</div>
						<a class="boxclose" id="boxclose"> <span> </span></a>
					</div>
				</div>
			</div>
		</div>
		<div class="userinfo">
			<div class="user">
				<ul>
                    <li><a href="<?php echo site_url('person/index')?>"><img src="<?php if (isset($user_image) && is_string($user_image)) : echo $user_image;else:echo site_url('assets/images/user-pic.png');endif;?>" title="user-img" id="person_image"  style="width: 50px;height: 50px;"/><span>个人资料</span></a></li>
                    <input type="file" name="person_image" style="display: none" id="person_image_upload" onchange="upload_image()">
                    <li><a><span id="ajax_logout" attr-href="<?php echo site_url('login/ajax_logout')?>">退出登录</span></li>
				</ul>
			</div>
		</div>
		<div class="clear"> </div>
	</div>
</div>
<!---//End-header---->
<!---start-content---->
<div class="content">
	<div class="wrap">
		<div class="contact-info">
			<div class="contact-grids">
				<div class="col_1_of_bottom span_1_of_first1">
					<h5>我发布过的信息</h5>
					<ul class="list3">
						<li>
							<div class="extra-wrap">
								<p>My message.</p>
							</div>
						</li>
					</ul>
				</div>
                <div class="col_1_of_bottom span_1_of_first1">
                    <h5></h5>
                    <ul class="list3">
                        <li>
                            <div class="extra-wrap">
                                <p></p>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="col_1_of_bottom span_1_of_first1">
                    <h5><a href="<?php echo site_url('article/index')?>">发布信息</a></h5>
                    <ul class="list3">
                        <li>
                            <div class="extra-wrap">
                                <p>Please click me !</p>
                            </div>
                        </li>
                    </ul>
                </div>
				<div class="clear"></div>
			</div>
			<form method="post" action="" onsubmit="false" id="article_form">
                <?php if (isset($articles_info) && !empty($articles_info) && is_array($articles_info)) :?>
                <?php foreach ($articles_info as $key => $value){?>
				<div class="contact-form">
                    <table style="line-height: 50px">
                        <tr>
                            <td width="300px;" style="font-size: large;"><a href="<?php echo $article_edit.'?article_id='.$value['id'] ?>"><?php $position = strpos($value['article_name'],' [转发]'); if ($position):echo substr($value['article_name'],0,$position);else:echo $value['article_name'];endif;?></a></td>
                            <td width="300px;">
                                <span>
                                    <?php if ($value['article_status'] == 1): echo '[已发布]';elseif ($value['article_status'] == 2) : echo '[草稿]';else: echo '[转发]';endif;?>
                                </span>
                            </td>
                            <td width="1300px"></td>
                            <td width="50px;"></td>
                            <td width="50px;">赞&nbsp;:&nbsp;<?php echo $value['good_num']?></td>
                        </tr>
                        <tr>
                            <td width="300px;" style="font-size: smaller;color: rgba(26,26,52,0.72)">更新时间: <?php echo $value['modification_time']?></td>
                            <td width="300px;" style="font-size: smaller;color: rgba(26,26,52,0.72)">发布时间: <?php echo $value['creation_time']?></td>
                            <td width="1300px"></td>
                            <td width="50px"><?php if ($value['article_status'] == 2):?><a href="<?php echo $article_edit.'?article_id='.$value['id'] ?>">编辑</a><?php endif;?></td>
                            <td width="50px"><a href="<?php echo $article_del.'?article_id='.$value['id'] ?>">删除</a></td>
                        </tr>
                    </table>
                    <hr>
				</div>
                <?php }?>
                <?php else:?>
                    <div class="contact-form">
                        <hr>
                        <br>
                        <br>
                         您没有发表任何信息哟~
                        <br>
                        <br>
                        <br>
                        <hr>
                    </div>
                <?php endif;?>
			</form>
		</div>
	</div>
</div>
<!----start-footer--->
<div class="footer">
    <p>Copyright &copy; 2018.Company name All rights reserved.More Templates <a href="" title="">版权最终解释权归uuJiang所有</a> - Collect from <a href="" title="" target="_blank">PHPerJiang</a></p>
</div>
<!----//End-footer--->
<!---//End-wrap---->
<input type="hidden" id="login_href" value="<?php echo site_url('login/index')?>">
<input type="hidden" id="id" value="<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0 ?>">
</body>
<script type="text/javascript">
    //ajax退出登录
    $('#ajax_logout').click(function () {
        var url = $('#ajax_logout').attr('attr-href');
        var jump_to = $('#login_href').val();
        $.ajax({
            url:url,
            type:"POST",
            dataType:'json',
            success:function (data) {
                if (data.error_code == 0){
                    window.location.href = jump_to;
                }
            },
        });
    });
</script>
</html>

