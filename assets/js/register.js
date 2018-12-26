//登录相关js
$(document).ready(function(){
    //定义全局变量
    var global_account;
    var global_password;
    var global_repassword;
    //错误提示显示时间
    var tips_show_time = 3500;

    //账户校验
    $('#register_account').blur(function () {
        var account = $('#register_account').val();
        var res = checkAccount(account);
        if (res){
            global_account = account;
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

    //禁止表单自动提交
    $('#register_form').submit(function (e) {
        e.preventDefault();
    });

    //校验通过则发送请求
    $('#register_btn').click(function () {
        if (global_account && global_password && global_repassword){
            $.ajax({
                type:"POST",
                url:"../../index.php/login/login",
                data:{"account":global_account,"password":global_password},
                dataType:'json',
                success:function (data) {
                    alert(data);
                },
                error:function (msg) {
                    alert(msg);
                }
            });
        }else{
            $('#register_tips').val('请输入要注册的账户名/密码').show().fadeOut(tips_show_time);
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