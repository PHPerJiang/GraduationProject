<!DOCTYPE HTML>
<html>
<head>
	<title>Person</title>
	<link href="<?php echo site_url('assets/css/style-feed.css')?>" rel='stylesheet' type='text/css' />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" type="image/x-icon" href="<?php echo site_url('assets/images/logo.png')?>" />
	<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800' rel='stylesheet' type='text/css'>
    <script src="<?php echo base_url('assets/js/jquery-3.1.0.min.js')?>"></script>
    <!--    加载百度的ueditor插件-->
    <script src="<?php echo base_url('assets/ueditor/ueditor.config.js')?>"></script>
    <script src="<?php echo base_url('assets/ueditor/ueditor.all.js')?>"></script>
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
                                <li><a href="<?php echo site_url('feed/index')?>"><span>热门信息</span></a></li>
                                <li><a href="<?php echo site_url('follow/follow_list')?>"><span>我的关注</span></a></li>
                                <li><a href="<?php echo site_url('article/index')?>"><span>发布信息</span></a></li>
                                <li><a href="<?php echo site_url('article_list/index')?>"><span>我的信息</span></a></li>
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
					<h5>标题</h5>
					<ul class="list3">
						<li>
							<div class="extra-wrap">
								<p>Please enter your info title.</p>
							</div>
						</li>
					</ul>
				</div>
				<div class="col_1_of_bottom span_1_of_first1">
					<h5>简介</h5>
					<ul class="list3">
						<li>
							<div class="extra-wrap">
								<p>Please enter brief introduction.</p>
							</div>
						</li>
					</ul>
				</div>
				<div class="col_1_of_bottom span_1_of_first1">
					<h5>作者</h5>
					<ul class="list3">
						<li>
                            <div class="extra-wrap">
                                <p>Automatic filling.</p>
                            </div>
						</li>
					</ul>
				</div>
				<div class="clear"></div>
			</div>
			<form method="post" action="" onsubmit="false" id="article_form">
				<div class="contact-form">
					<div class="contact-to">
						<input type="text" class="text" name="article_name" id="article_name"  placeholder="Please enter your info title."
                               value="<?php echo isset($articles_info['article_name']) ? $articles_info['article_name'] : '' ?>" readonly>

						<input type="text" class="text" name="article_intro" id="article_intro" placeholder="Please enter brief introduction."
                               value="<?php echo isset($articles_info['article_intro']) ? $articles_info['article_intro'] : '' ?>" readonly>

						<input type="text" class="text" name="article_author" id="article_author" placeholder="Automatic filling."
                               value="<?php echo isset($articles_info['article_author']) ? $articles_info['article_author'] : (isset($user_nickname) ? $user_nickname : '') ?>" readonly>
					</div><br/><br/><br/><br/>
					<div class="text2">
						<textarea  name="article_content" id="article_content"  placeholder="Please enter what you think..." style="height: 400px;">
                        </textarea>
					</div>
                    <br/>
                    <div class="clear">
                        <span><a href="javascript:void(0)" ><image src = "<?php echo $articles_info['user_is_evaluated'] ? site_url('assets/images/gooded.png') :site_url('assets/images/good.png')?>"
                                                                   style="vertical-align: middle"  id="evaluate_good" attr-status = "<?php echo $articles_info['user_is_evaluated'] ? 0 : 1 ?>"</image></a></span>
                        <label id="article_good_num"><?php echo $articles_info['good']?></label>
                        &nbsp;&nbsp;&nbsp;
                        <span><button  style="vertical-align: middle" id="follow" value="<?php echo empty($articles_info['is_followed']) ? 1 : 0 ?>"><?php echo empty($articles_info['is_followed']) ? '关注作者' : '取消关注作者' ?></button></span>
                    </div>
				</div>
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
<input type="hidden" id="user_follow_href" value="<?php echo site_url('follow/user_follow')?>">
<input type="hidden" id="user_unfollow_href" value="<?php echo site_url('follow/user_unfollow')?>">
<input type="hidden" id="article_user_id" value="<?php echo $articles_info['user_id']?>">
<input type="hidden" id="id" value="<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0 ?>">
<input type="hidden" id="article_id" value="<?php echo isset($articles_info['id']) ? $articles_info['id'] : 0 ?>">
<input type="hidden" id="article_list_href" value="<?php echo site_url('article_list/index')?>">
<code style="display: none" id="html_content">
	<?php echo isset($articles_info['article_content']) ? $articles_info['article_content'] : ''?>
</code>
<script src="<?php echo base_url('assets/js/article.js')?>"></script>
</body>
</html>

