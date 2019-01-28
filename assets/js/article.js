$(document).ready(function () {
    //全局函数
    var global_article_name;
    var global_article_intro;
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
            $('#article_tips').val('标题不能为空').show().fadeOut(tips_show_time);
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
            $('#article_tips').val('作者不能为空').show().fadeOut(tips_show_time);
        }else {
            global_article_author = article_author;
        }
    });
    //发布：内容不为空则赋值全局变量
    $('#atricle_release_btn').click(function () {
        var article_content = validate_article_content();
        if(article_content){
            global_article_content = article_content;
            global_article_status = 1;
        }
    });
    //草稿：内容不为空则赋值全局变量
    $('#article_draft_btn').click(function () {
        var article_content = validate_article_content();
        if(article_content){
            global_article_content = article_content;
            global_article_status = 2;
        }
    });

    //验证内容是否为空
    function validate_article_content(){
        var article_content = ue.getContent();
        if (article_content){
            return article_content;
        }else {
            return false;
        }
    }
});

