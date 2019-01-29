$(document).ready(function () {
    //全局函数
    var global_article_name;
    var global_article_intro ;
    var global_article_author;
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
                    'user_id' : $('#id').val(),
                },
                success:function (data) {
                    
                },
                error:function (error) {
                    
                }
            });
        }else {
            $('#article_tips').val('标题、作者、内容不能为空，请填写！').show().fadeOut(tips_show_time);
        }
    }
});

