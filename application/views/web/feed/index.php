<!DOCTYPE HTML>
<html>
<head>
	<title>Home</title>
	<link href="<?php echo site_url('assets/css/style-feed.css')?>" rel='stylesheet' type='text/css' />
    <meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" type="image/x-icon" href="<?php echo site_url('assets/images/logo.png')?>" />
	<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800' rel='stylesheet' type='text/css' />
    <link rel="stylesheet" href="<?php echo site_url('assets/css/main-feed.css')?>" />
    <script src="<?php echo site_url('assets/js/jquery-3.1.0.min.js')?>"></script>
    <script src="<?php echo site_url('assets/js/common.js')?>"></script>
	<script type="text/javascript">
        //控制导航栏的js
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
</head>
<body>
<!---start-wrap---->
<!---start-header---->
<div class="header">
	<div class="wrap">
		<div class="logo">
			<a href=""><img src="<?php echo site_url('assets/images/logo.png')?>" title="pinbal" /></a>
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
								<li><a href="#"><span>热门信息</span></a></li>
								<li><a href="#"><span>我的关注</span></a></li>
								<li><a href="#"><span>发布信息</span></a></li>
								<li><a href="#"><span>我的信息</span></a></li>
								<div class="clear"> </div>
							</ul>
						</div>
						<a class="boxclose" id="boxclose"> <span> </span></a>
					</div>
				</div>
			</div>
		</div>
		<div class="top-searchbar">
			<form>
				<input type="text" /><input type="submit" value="" />
			</form>
		</div>
		<div class="userinfo">
			<div class="user">
				<ul>
					<li><a href="#"><img src="<?php echo site_url('assets/images/user-pic.png')?>" title="user-name" /><span>个人资料</span></a></li>
                    <li><a href="<?php echo site_url('login/logout')?>"  id="logout" ><span>退出登录</span></li>
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
		<div id="main" role="main">
			<ul id="tiles">
				<!-- These are our grid blocks -->
				<li onClick="location.href='single-page.html';">
					<img src="<?php echo site_url('assets/images/img1.jpg')?>" width="200" height="200">
					<div class="post-info">
						<div class="post-basic-info">
							<h3><a href="#">Animation films</a></h3>
							<span><a href="#"><label> </label>Movies</a></span>
							<p>Lorem Ipsum is simply dummy text of the printing & typesetting industry.</p>
						</div>
						<div class="post-info-rate-share">
							<div class="rateit">
								<span> </span>
							</div>
							<div class="post-share">
								<span> </span>
							</div>
							<div class="clear"> </div>
						</div>
					</div>
				</li>
				<li onClick="location.href='single-page.html';">
					<img src="<?php echo site_url('assets/images/img2.jpg')?>" width="200" height="299">
					<div class="post-info">
						<div class="post-basic-info">
							<h3><a href="#">Animation films</a></h3>
							<span><a href="#"><label> </label>Movies</a></span>
							<p>Lorem Ipsum is simply dummy text of the printing & typesetting industry.</p>
						</div>
						<div class="post-info-rate-share">
							<div class="rateit">
								<span> </span>
							</div>
							<div class="post-share">
								<span> </span>
							</div>
							<div class="clear"> </div>
						</div>
					</div>
				</li>
				<li onClick="location.href='single-page.html';">
					<img src="<?php echo site_url('assets/images/img3.jpg')?>" width="200" height="214">
					<div class="post-info">
						<div class="post-basic-info">
							<h3><a href="#">Animation films</a></h3>
							<span><a href="#"><label> </label>Movies</a></span>
							<p>Lorem Ipsum is simply dummy text of the printing & typesetting industry.</p>
						</div>
						<div class="post-info-rate-share">
							<div class="rateit">
								<span> </span>
							</div>
							<div class="post-share">
								<span> </span>
							</div>
							<div class="clear"> </div>
						</div>
					</div>
				</li>
				<li onClick="location.href='single-page.html';">
					<img src="<?php echo site_url('assets/images/img4.jpg')?>" width="200" height="333">
					<div class="post-info">
						<div class="post-basic-info">
							<h3><a href="#">Animation films</a></h3>
							<span><a href="#"><label> </label>Movies</a></span>
							<p>Lorem Ipsum is simply dummy text of the printing & typesetting industry.</p>
						</div>
						<div class="post-info-rate-share">
							<div class="rateit">
								<span> </span>
							</div>
							<div class="post-share">
								<span> </span>
							</div>
							<div class="clear"> </div>
						</div>
					</div>
				</li>
				<!----//--->
				<li onClick="location.href='single-page.html';">
					<img src="<?php echo site_url('assets/images/img4.jpg')?>" width="200" height="333">
					<div class="post-info">
						<div class="post-basic-info">
							<h3><a href="#">Animation films</a></h3>
							<span><a href="#"><label> </label>Movies</a></span>
							<p>Lorem Ipsum is simply dummy text of the printing & typesetting industry.</p>
						</div>
						<div class="post-info-rate-share">
							<div class="rateit">
								<span> </span>
							</div>
							<div class="post-share">
								<span> </span>
							</div>
							<div class="clear"> </div>
						</div>
					</div>
				</li>
				<li onClick="location.href='single-page.html';">
					<img src="<?php echo site_url('assets/images/img3.jpg')?>" width="200" height="214">
					<div class="post-info">
						<div class="post-basic-info">
							<h3><a href="#">Animation films</a></h3>
							<span><a href="#"><label> </label>Movies</a></span>
							<p>Lorem Ipsum is simply dummy text of the printing & typesetting industry.</p>
						</div>
						<div class="post-info-rate-share">
							<div class="rateit">
								<span> </span>
							</div>
							<div class="post-share">
								<span> </span>
							</div>
							<div class="clear"> </div>
						</div>
					</div>
				</li>
				<li onClick="location.href='single-page.html';">
					<img src="<?php echo site_url('assets/images/img2.jpg')?>" width="200" height="299">
					<div class="post-info">
						<div class="post-basic-info">
							<h3><a href="#">Animation films</a></h3>
							<span><a href="#"><label> </label>Movies</a></span>
							<p>Lorem Ipsum is simply dummy text of the printing & typesetting industry.</p>
						</div>
						<div class="post-info-rate-share">
							<div class="rateit">
								<span> </span>
							</div>
							<div class="post-share">
								<span> </span>
							</div>
							<div class="clear"> </div>
						</div>
					</div>
				</li>
				<li onClick="location.href='single-page.html';">
					<img src="<?php echo site_url('assets/images/img1.jpg')?>" width="200" height="200">
					<div class="post-info">
						<div class="post-basic-info">
							<h3><a href="#">Animation films</a></h3>
							<span><a href="#"><label> </label>Movies</a></span>
							<p>Lorem Ipsum is simply dummy text of the printing & typesetting industry.</p>
						</div>
						<div class="post-info-rate-share">
							<div class="rateit">
								<span> </span>
							</div>
							<div class="post-share">
								<span> </span>
							</div>
							<div class="clear"> </div>
						</div>
					</div>
				</li>
				<!----//--->
				<li onClick="location.href='single-page.html';">
					<img src="<?php echo site_url('assets/images/img1.jpg')?>" width="200" height="200">
					<div class="post-info">
						<div class="post-basic-info">
							<h3><a href="#">Animation films</a></h3>
							<span><a href="#"><label> </label>Movies</a></span>
							<p>Lorem Ipsum is simply dummy text of the printing & typesetting industry.</p>
						</div>
						<div class="post-info-rate-share">
							<div class="rateit">
								<span> </span>
							</div>
							<div class="post-share">
								<span> </span>
							</div>
							<div class="clear"> </div>
						</div>
					</div>
				</li>
				<li onClick="location.href='single-page.html';">
					<img src="<?php echo site_url('assets/images/img2.jpg')?>" width="200" height="299">
					<div class="post-info">
						<div class="post-basic-info">
							<h3><a href="#">Animation films</a></h3>
							<span><a href="#"><label> </label>Movies</a></span>
							<p>Lorem Ipsum is simply dummy text of the printing & typesetting industry.</p>
						</div>
						<div class="post-info-rate-share">
							<div class="rateit">
								<span> </span>
							</div>
							<div class="post-share">
								<span> </span>
							</div>
							<div class="clear"> </div>
						</div>
					</div>
				</li>
				<li onClick="location.href='single-page.html';">
					<img src="<?php echo site_url('assets/images/img3.jpg')?>" width="200" height="214">
					<div class="post-info">
						<div class="post-basic-info">
							<h3><a href="#">Animation films</a></h3>
							<span><a href="#"><label> </label>Movies</a></span>
							<p>Lorem Ipsum is simply dummy text of the printing & typesetting industry.</p>
						</div>
						<div class="post-info-rate-share">
							<div class="rateit">
								<span> </span>
							</div>
							<div class="post-share">
								<span> </span>
							</div>
							<div class="clear"> </div>
						</div>
					</div>
				</li>
				<li onClick="location.href='single-page.html';">
					<img src="<?php echo site_url('assets/images/img4.jpg')?>" width="200" height="333">
					<div class="post-info">
						<div class="post-basic-info">
							<h3><a href="#">Animation films</a></h3>
							<span><a href="#"><label> </label>Movies</a></span>
							<p>Lorem Ipsum is simply dummy text of the printing & typesetting industry.</p>
						</div>
						<div class="post-info-rate-share">
							<div class="rateit">
								<span> </span>
							</div>
							<div class="post-share">
								<span> </span>
							</div>
							<div class="clear"> </div>
						</div>
					</div>
				</li>
				<!-- End of grid blocks -->
			</ul>
		</div>
	</div>
</div>
<script src="<?php echo site_url('assets/js/script-feed.js')?>"></script>
<div class="footer">
	<p>Copyright &copy; 2015.Company name All rights reserved.More Templates <a href="" target="_blank" title="">版权最终解释权归uuJiang所有</a> - Collect from <a href="" title="" target="_blank">PHPerJiang</a></p>
</div>
<!----//End-footer--->
<!---//End-wrap---->
</body>
</html>

