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
		<div class="top-searchbar">
			<form>
				<input type="text" /><input type="submit" value="" />
			</form>
		</div>
		<div class="userinfo">
			<div class="user">
				<ul>
					<li><a href="<?php echo site_url('person/index')?>" ><img src="<?php echo isset($user_image) ? $user_image : site_url('assets/images/user-pic.png')?>" title="user-name"  style="width: 50px;height: 50px;"/><span>个人资料</span></a></li>
                    <li><a href="<?php echo site_url('login/logout')?>"  id="logout" ><span>退出登录</span></a></li>
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
				<?php if (isset($articles_info) && !empty($articles_info)):?>
					<?php foreach ($articles_info as $key => $value){?>
                        <a href="<?php echo site_url('article/read').'?article_id='.$value['user_id'].':'.$value['id']?>" target="_blank">
                            <li>
                                <img src="<?php echo  !empty($value['image']) ? site_url("assets/{$value['image']}") :  site_url('assets/images/img4.jpg')?>" width="200" height="333">
                                <div class="post-info">
                                    <div class="post-basic-info">
                                        <h3><a href="#"><?php echo $value['article_name']?></a></h3>
                                        <span><a href="#"><label></label><?php echo $value['article_author']?></a></span>
                                        <p><?php echo $value['article_intro']?></p>
                                    </div>
                                    <div class="post-info-rate-share">
                                        <div style="float: left">
                                            &nbsp;<span style="font-size: smaller;color: rgba(96,98,138,0.34);"><?php echo $value['modification_time']?></span>
                                        </div>
                                        <div style="float: right">
                                            <div>
                                                <span style="display: inline-block;vertical-align: middle"><image src ="<?php echo site_url('assets/images/gooded.png') ?>" style="width: 20px;height: 20px;" ></span>
                                                <span  style="display: inline-block;vertical-align: middle"><?php echo $value['good_num']?>&nbsp;&nbsp;</span>
                                            </div>
                                        </div>
                                        <div class="clear"> </div>
                                    </div>
                                </div>
                            </li>
                        </a>
					<?php } endif;?>
                <!-- End of grid blocks -->
            </ul>
		</div>
	</div>
</div>
<script src="<?php echo site_url('assets/js/script-feed.js')?>"></script>
<script>
    (function ($){
        var $tiles = $('#tiles'),
            $handler = $('li', $tiles),
            $main = $('#main'),
            $window = $(window),
            $document = $(document),
            options = {
                autoResize: true, // This will auto-update the layout when the browser window is resized.
                container: $main, // Optional, used for some extra CSS styling
                offset: 20, // Optional, the distance between grid items
                itemWidth:280 // Optional, the width of a grid item
            };
        /**
         * Reinitializes the wookmark handler after all images have loaded
         */
        function applyLayout() {
            $tiles.imagesLoaded(function() {
                // Destroy the old handler
                if ($handler.wookmarkInstance) {
                    $handler.wookmarkInstance.clear();
                }

                //创建处理对象
                $handler = $('li', $tiles);
                $handler.wookmark(options);
            });
        }

        /**
         * 滚动事件函数
         */
        function onScroll() {
            // 设置触发高度
            var winHeight = window.innerHeight ? window.innerHeight : $window.height(), // iphone fix
                closeToBottom = ($window.scrollTop() + winHeight > $document.height() - 100);
            //获取当前ul对象
            var tiles = document.getElementById('tiles');
            //获取当前ul下li标签的个数
            var li_num = tiles.getElementsByTagName('li').length;
            //赋值偏移量及每次增量请求个数
            var offset = li_num;
            var size = offset + 10;
            if (closeToBottom) {
                $.ajax({
                    url:'more_feed_info',
                    type:'POST',
                    dataType:'json',
                    data:{'offset':offset,'size':size},
                    success:function (data) {
                        if (data.error_code == 0){
                            for (var i = 0 ; i < data.rdata.length ; i++){
                                //拼凑html
                                var popContent =
                                    '<a href="'+data.rdata[i].jump_to+'" target="_blank">'+
                                    '<li>'+
                                    '<img src="'+data.rdata[i].image+'" width="200" height="333">'+
                                    '<div class="post-info">'+
                                    '<div class="post-basic-info">'+
                                    ' <h3><a href="#">'+data.rdata[i].article_name+'</a></h3>'+
                                    '<span><a href="#"><label></label>'+data.rdata[i].article_author+'</a></span>'+
                                    '<p>'+data.rdata[i].article_intro+'</p>'+
                                    '</div>'+
                                    '<div class="post-info-rate-share">'+
                                    '<div style="float: left">'+
                                    '&nbsp;<span style="font-size: smaller;color: rgba(96,98,138,0.34)">'+data.rdata[i].modification_time+'</span>'+
                                    '</div>'+
                                    '<div style="float: right">'+
                                    '<div>'+
                                    ' <span style="display: inline-block;vertical-align: middle">'+ '<img src="'+data.rdata[i].good_pic+'"></span>'+
                                    ' <span style="display: inline-block;vertical-align: middle">'+data.rdata[i].good_num +'&nbsp;&nbsp;</span>'+
                                    '</div>'+
                                    '</div>'+
                                    '<div class="clear"> </div>'+
                                    '</div>'+
                                    '</div>'+
                                    '</li>'+
                                    '</a>'
                                //将拼凑好的html追加到对象中
                                $tiles.append(popContent);
                                applyLayout();
                            }
                        }
                    },
                    error:function (error) {
                        alert('网络异常');
                    }
                });

            }
        };

        applyLayout();

        $window.bind('scroll.wookmark', onScroll);
    })(jQuery);
</script>
<div class="footer">
	<p>Copyright &copy; 2015.Company name All rights reserved.More Templates <a href="" target="_blank" title="">版权最终解释权归uuJiang所有</a> - Collect from <a href="" title="" target="_blank">PHPerJiang</a></p>
</div>
<!----//End-footer--->
<!---//End-wrap---->
</body>
</html>

