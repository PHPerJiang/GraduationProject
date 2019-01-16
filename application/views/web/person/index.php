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
			<a href="index.html"><img src="<?php echo site_url('assets/images/logo.png')?>" title="pinbal" /></a>
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
		<div class="userinfo">
			<div class="user">
				<ul>
                    <li><a href="#"><img src="<?php echo site_url('assets/images/user-pic.png')?>" title="user-img" /><span>个人资料</span></a></li>
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
					<h5>真实姓名</h5>
					<ul class="list3">
						<li>
							<div class="extra-wrap">
								<p>Please enter your real name.</p>
							</div>
						</li>
					</ul>
				</div>
				<div class="col_1_of_bottom span_1_of_first1">
					<h5>账号昵称</h5>
					<ul class="list3">
						<li>
							<div class="extra-wrap">
								<p>Please enter your name.</p>
							</div>
						</li>
					</ul>
				</div>
				<div class="col_1_of_bottom span_1_of_first1">
					<h5>联系电话</h5>
					<ul class="list3">
						<li>
                            <div class="extra-wrap">
                                <p>Please enter your phone.</p>
                            </div>
						</li>
					</ul>
				</div>
				<div class="clear"></div>
			</div>
			<form method="post" action="">
				<div class="contact-form">
					<div class="contact-to">
						<input type="text" class="text" name="person_name" placeholder="Please enter your real name.">
						<input type="text" class="text" name="person_nickname" placeholder="Please enter your nickname.">
						<input type="text" class="text" name="person_phone" placeholder="Please enter your phone.">
					</div>
					<div class="text2">
						<textarea  name="person_description" placeholder="Description"></textarea>
					</div>
					<span><input type="submit" id="person_btn" class="" value="提交"></span>
					<div class="clear"></div>
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
<script src="<?php echo base_url('assets/js/person.js')?>"></script>
</body>
</html>

