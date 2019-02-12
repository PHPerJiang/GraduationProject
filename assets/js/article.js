$(document).ready(function () {
    //全局函数
    var global_article_name = $('#article_name').val();
    var global_article_intro = $('#article_intro').val();
    var global_article_author = $('#article_author').val();
    var global_article_content;
    var global_article_status;

    //提示显示时间
    var tips_show_time = 3500;

    /**
     * 加载百度Ueditor富文本编辑工具
     */
    var ue = UE.getEditor('article_content',{
        initialFrameWidth:'97.8%' , //初始化编辑器宽度,默认1000
        initialFrameHeight:400
    });
    //获取库中数据并加载进editor
    var html_content = $('#html_content').html();
    if (html_content){
        //停1s后加载
        window.setTimeout(setContent,1000);
    }
    function setContent(){
        ue.execCommand('insertHtml',html_content);
        //如果是读取页则隐藏编辑器并不可编辑
        var now_page = self.location.href;
        if (now_page.search('read') != -1){
            ue.setDisabled();
            // ue.readAsText();
            // ue.setHide();
        }
    }
    /**
     * 阻止表单提交
     */
    $('#article_form').submit(function (e) {
        e.preventDefault();
    })

    /**
     * 标题判断
     */
    $('#article_name').blur(function () {
        var article_name = $(this).val();
        if ($.trim(article_name) ==  ''){
            $('#article_tips').val('标题不能为空！').show().fadeOut(tips_show_time);
        }else {
            global_article_name = article_name;
        }
    });

    /**
     * 作者判空
     */
    $('#article_author').blur(function () {
        var article_author = $(this).val();
        if($.trim(article_author) == ''){
            $('#article_tips').val('作者不能为空！').show().fadeOut(tips_show_time);
        }else {
            global_article_author = article_author;
        }
    });

    //发布：内容不为空则赋值全局变量
    $('#atricle_release_btn').click(function () {
        validate_article_content(1);
    });

    //草稿：内容不为空则赋值全局变量
    $('#article_draft_btn').click(function () {
        validate_article_content(2);
    });

    //验证内容是否为空,不为空则发送请求
    function validate_article_content(article_status){
        var article_content = ue.getContent();
        if (article_content){
            global_article_content = article_content;
            global_article_status = article_status;
        }else {
            $('#article_tips').val('内容不能为空！').show().fadeOut(tips_show_time);
        }
        if (global_article_name && global_article_author && global_article_content && global_article_status){
            global_article_intro = $('#article_intro').val();
            $.ajax({
                url:'save_article',
                type:'POST',
                dataType:'json',
                data:{
                    'article_name' : global_article_name,
                    'article_intro' : global_article_intro,
                    'article_author' : global_article_author,
                    'article_content' : global_article_content,
                    'article_status' : global_article_status,
                    'article_id': $('#article_id').val(),
                    'user_id' : $('#id').val(),
                },
                success:function (data) {
                    if (data.error_code != 0){
                        $('#article_tips').val(data.error_msg).show().fadeOut(tips_show_time);
                    }else {
                        $('#article_tips').css('color','green').val('存储成功,3秒后跳转至已发布信息列表页').show().fadeOut(tips_show_time);
                        window.setTimeout(goto_article_list,3000);
                    }
                },
                error:function (error) {
                    $('#article_tips').val('网络错误').show().fadeOut(tips_show_time);
                }
            });
        }else {
            $('#article_tips').val('标题、作者、内容不能为空，请填写！').show().fadeOut(tips_show_time);
        }
    }

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

    function goto_article_list() {
        window.location.href = $('#article_list_href').val();
    }

    //点击关注、取消关注
    $('#follow').click(function () {
        var follow_status = $(this).val();
        if (follow_status == 1){
            $.ajax({
                url:$('#user_follow_href').val(),
                type:'POST',
                dataType:'json',
                data:{'user_follow_id':$('#article_user_id').val()},
                success:function (data) {
                    if(data.error_code == 0){
                        $('#follow').html('取消关注作者');
                        $('#follow').val('0');

                    }
                },
                error:function (error) {
                    alert('网络异常');
                }
            });
        }else {
            $.ajax({
                url:$('#user_unfollow_href').val(),
                type:'POST',
                dataType:'json',
                data:{'user_follow_id':$('#article_user_id').val()},
                success:function (data) {
                    if(data.error_code == 0){
                        $('#follow').html('关注作者');
                        $('#follow').val('1');
                    }
                },
                error:function (error) {
                    alert('网络异常');
                }
            });
        }

    });

    //点赞
    $('#evaluate_good').click(function () {
        var evaluate_statis = $(this).attr('attr-status');
        if (evaluate_statis == 1){
            $.ajax({
                url:'evaluate',
                type:'POST',
                dataType:'json',
                data:{
                    'article_id':$("#article_id").val(),
                    'article_user_id':$("#article_user_id").val(),
                    'evaluate_type' : 'add',
                },
                success:function (data) {
                    if (data.error_code == 0){
                        $('#evaluate_good').attr('src',"http://localhost/GraduationProject/assets/images/gooded.png");
                        var article_good_num = parseInt($('#article_good_num').html()) + 1;
                        $('#article_good_num').html(article_good_num);
                        $('#evaluate_good').attr('attr-status',0);
                    }
                },
                error:function (error) {
                    alert('网络错误');
                }
            });
        } else {
            $.ajax({
                url:'evaluate',
                type:'POST',
                dataType:'json',
                data:{
                    'article_id':$("#article_id").val(),
                    'article_user_id':$("#article_user_id").val(),
                    'evaluate_type' : 'del',
                },
                success:function (data) {
                    if (data.error_code == 0){
                        $('#evaluate_good').attr('src',"http://localhost/GraduationProject/assets/images/good.png");
                        var article_good_num = parseInt($('#article_good_num').html()) - 1;
                        $('#article_good_num').html(article_good_num);
                        $('#evaluate_good').attr('attr-status',1);
                    }
                },
                error:function (error) {
                    alert('网络错误');
                }
            });
        }

    });

    function evaluate() {
        
    }
});

