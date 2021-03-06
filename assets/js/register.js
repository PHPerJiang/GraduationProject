//注册相关js
$(document).ready(function(){
    //定义全局变量
    var global_account;
    var global_password;
    var global_repassword;
    var global_captach;
    //错误提示显示时间
    var tips_show_time = 3500;

    //账户校验
    $('#register_account').blur(function () {
        var account = $('#register_account').val();
        var res = checkAccount(account);
        if (res){
            $.ajax({
               type:"POST",
                url:"validate_account",
                data:{'account':account},
                dataType:'json',
                success:function (data) {
                    if (data.error_code != 0){
                        $('#register_tips').val('账号已被注册,请使用其他账号').show().fadeOut(tips_show_time);
                    }else {
                        global_account = account;
                    }
                },
                error:function (error) {
                    $('#register_tips').val('网络错误').show().fadeOut(tips_show_time);
                }
            });
        }else {
            $('#register_account').val('');
            $('#register_tips').val('账号由字母，数字，下划线组成，字母开头，4-16位').show().fadeOut(tips_show_time);
        }
    });

    //密码校验
    $('#register_password').blur(function () {
        var password = $('#register_password').val();
        var res = checkPassword(password);
        if (res){
            global_password = password;
        }else {
            $('#register_password').val('');
            $('#register_tips').val('密码必须由6-12个大小写字母或数字组成').show().fadeOut(tips_show_time);
        }
    });
    $('#register_repassword').blur(function () {
        var repassword = $('#register_repassword').val();
        var res = checkPassword(repassword);
        if (res){
            global_repassword = repassword;
        }else {
            $('#register_repassword').val('');
            $('#register_tips').val('密码必须由6-12个大小写字母或数字组成').show().fadeOut(tips_show_time);
        }
        if (global_password && global_repassword && (global_repassword !== global_password)){
            $('#register_repassword').val('');
            $('#register_tips').val('两次输入密码不一致').show().fadeOut(tips_show_time);
        }
    });

    //验证码校验
    $('#register_captach').blur(function () {
        var register_captach = $('#register_captach').val();
        var register_captcha_img_src = $('#register_captcha_img').attr('src');
        register_captcha_img_src = register_captcha_img_src + '?id='+Math.random();
        if (!register_captach){
            $('#register_captach').val('');
            $('#register_tips').val('请输入验证码').show().fadeOut(tips_show_time);
        }
        $.ajax({
            type:'POST',
            dataType:'json',
            url:'validate_captcha',
            data:{'code':register_captach},
            success:function (data) {
                if (data.error_code == 3){
                    $('#register_captach').val('');
                    $('#register_captcha_img').attr('src',register_captcha_img_src);
                    $('#register_tips').val('验证码错误').show().fadeOut(tips_show_time);
                }else if (data.error_code == 0){
                    global_captach = register_captach;
                }
            } ,
            error:function (err) {
                $('#register_captach').val('');
                $('#register_captcha_img').attr('src',register_captcha_img_src);
                $('#register_tips').val('网络错误').show().fadeOut(tips_show_time);
            }
        });
    });

    //禁止表单自动提交
    $('#register_form').submit(function (e) {
        e.preventDefault();
    });

    //校验通过则发送请求
    $('#register_btn').click(function () {
        if (global_account && global_password && global_repassword && global_captach){
            $.ajax({
                type:"POST",
                url:"register",
                data:{"account":global_account,"password":global_password},
                dataType:'json',
                success:function (data) {
                    if(data.error_code != 0){
                        $('#register_tips').val('账户名/密码不正确').show().fadeOut(tips_show_time);
                    }else {
                        window.location.href = data.rdata.jump_to;
                    }
                },
                error:function (err) {
                    $('#register_tips').val('网络错误').show().fadeOut(tips_show_time);
                }
            });
        }else if(!global_account || !global_password || !global_repassword){
            $('#register_tips').val('请输入要注册的账户名/密码').show().fadeOut(tips_show_time);
        }else if (!global_captach) {
            $('#register_tips').val('请输入验证码').show().fadeOut(tips_show_time);
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