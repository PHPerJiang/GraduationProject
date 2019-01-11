//登录相关js
$(document).ready(function(){
    //定义全局变量
    var global_account;
    var global_password;
    var global_captach;
    //错误提示显示时间
    var tips_show_time = 3500;

    //账户校验
    $('#login_account').blur(function () {
        var account = $('#login_account').val();
        var res = checkAccount(account);
        if (res){
            global_account = account;
        }else {
            $('#login_account').val('');
            $('#login_tips').val('账号由字母，数字，下划线组成，字母开头，4-16位').show().fadeOut(tips_show_time);
        }
    });

    //密码校验
    $('#login_password').blur(function () {
        var password = $('#login_password').val();
        var res = checkPassword(password);
        if (res){
            global_password = password;
        }else {
            $('#login_password').val('');
            $('#login_tips').val('密码必须由6-12个大小写字母或数字组成').show().fadeOut(tips_show_time);
        }
    });

    //验证码校验
    $('#login_captach').blur(function () {
        var login_captach = $('#login_captach').val();
        var login_captcha_img_src = $('#login_captcha_img').attr('src');
        login_captcha_img_src = login_captcha_img_src + '?id='+Math.random();
        if (!login_captach){
            $('#login_captach').val('');
            $('#login_tips').val('请输入验证码').show().fadeOut(tips_show_time);
        }
        $.ajax({
            type:'POST',
            dataType:'json',
            url:'validate_captcha',
            data:{'code':login_captach},
            success:function (data) {
                if (data.error_code == 3){
                    $('#login_captach').val('');
                    $('#login_captcha_img').attr('src',login_captcha_img_src);
                    $('#login_tips').val('验证码错误').show().fadeOut(tips_show_time);
                }else if (data.error_code == 0){
                    global_captach = login_captach;
                }
            } ,
            error:function (err) {
                $('#login_captach').val('');
                $('#login_captcha_img').attr('src',login_captcha_img_src);
                $('#login_tips').val('网络错误').show().fadeOut(tips_show_time);
            }
        });
    });

    //禁止表单自动提交
    $('#login_form').submit(function (e) {
        e.preventDefault();
    });

    //校验通过则发送请求
    $('#login_btn').click(function () {
        if (global_account && global_password &&  global_captach){
            $.ajax({
                type:"POST",
                url:"login",
                data:{"account":global_account,"password":global_password},
                dataType:'json',
                success:function (data) {
                    if(data.error_code != 0){
                        $('#login_tips').val('账户名/密码不正确').show().fadeOut(tips_show_time);
                    }else {
                        window.location.href= $('#home_url').attr('href');
                    }
                },
                error:function (err) {
                    $('#login_tips').val('网络错误').show().fadeOut(tips_show_time);
                }
            });
        }else if(!global_account || !global_password){
            $('#login_tips').val('请输入要注册的账户名/密码').show().fadeOut(tips_show_time);
        }else if (!global_captach) {
            $('#login_tips').val('请输入验证码').show().fadeOut(tips_show_time);
        }
    })

});

//账号验证规则：字母，数字，下划线组成，字母开头，4-16位
function checkAccount(str){
    var re=/^[a-zA-z]\w{5,15}$/;
    if(re.test(str)){
        return true;
    }else {
        return false;
    }
}

//密码验证规则：密码由6-12个大小写字母或数字组成
function checkPassword(str){
    var re=/^[\w_-]{6,16}$/;
    if(re.test(str)){
        return true;
    }else {
        return false;
    }
}