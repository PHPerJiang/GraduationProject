<!DOCTYPE HTML>
<html>
<head>
	<title>MyFollow</title>
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
					<h5><a href="<?php echo site_url('follow/follow_user_list')?>">我关注的用户</a></h5>
					<ul class="list3">
						<li>
							<div class="extra-wrap">
								<p>My concern.</p>
							</div>
						</li>
					</ul>
				</div>
                <div class="col_1_of_bottom span_1_of_first1">
                    <h5><a href="javascript:void(0)">粉丝列表</a><span style="font-size: large;color: red">&nbsp;(您有&nbsp;<?php echo $fans_num?>&nbsp;位粉丝)</span></h5>
                    <ul class="list3">
                        <li>
                            <div class="extra-wrap">
                                <p>Fans list.</p>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="col_1_of_bottom span_1_of_first1">
                    <h5><a href="<?php echo site_url('rank/index')?>">排行榜</a></h5>
                    <ul class="list3">
                        <li>
                            <div class="extra-wrap">
                                <p>User ranking .</p>
                            </div>
                        </li>
                    </ul>
                </div>
				<div class="clear"></div>
			</div>
			<form method="post" action="" onsubmit="false" id="article_form">
                <?php if (isset($fans_info) && !empty($fans_info) && is_array($fans_info)) :?>
                 <div class="contact-form">
                     <table style="line-height: 50px;border-collapse:separate; border-spacing:0px 10px;border-bottom:solid 1px #333333;border-top:solid 1px #333333;">
                         <td width="700px;"></td>
                         <td width="300px;"></td>
                         <td width="300px;" style="font-size: larger;font-weight: bold">粉丝名</td>
                         <td width="300px;" style="font-size: larger;font-weight: bold">简介</td>
                         <td width="700px"></td>
		                 <?php foreach ($fans_info as $key => $value){?>
                             <tr id="user_info_<?php echo $value['user_id']?>">
                                 <td width="700px"></td>
                                 <td width="300px;" style="vertical-align: middle"><img src="<?php echo $value['image']?>" style="width: 60px;height: 60px;" /></td>
                                 <td width="300px;"  style="font-weight: bold;color: rgba(26,26,52,0.72)"><?php echo $value['nickname']?></td>
                                 <td width="300px;" style="font-weight: bold;color: rgba(26,26,52,0.72)"><?php echo $value['description']?></td>
                                 <td width="700px"></td>
                             </tr>
		                 <?php }?>
                     </table>
                 </div>
                <?php else:?>
                    <div class="contact-form">
                        <hr>
                        <br>
                        <br>
                         您没有任何粉丝哟~
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

    /**
     * 关注事件
     * @param user_id
     */
     function follow_event(user_id) {
         if (!user_id){
             alert('用户异常');
         }else {
             var tag_name = '#user_info_'+user_id;
             console.log($(tag_name));
             $.ajax({
                 url:'user_unfollow',
                 type:'POST',
                 datatype:'json',
                 data:{'user_follow_id':user_id},
                 success:function (data) {
                    if (data.error_code == 0){
                        $(tag_name).remove();
                    }else {
                        alert('网络错误，请稍后重试！');
                    }
                 },
                 error:function (error) {
                     alert('网络错误，请稍后重试！');
                 }
             });
         }

     }
</script>
</html>

