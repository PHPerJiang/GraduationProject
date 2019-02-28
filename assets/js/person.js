$(document).ready(function () {
    //声明全局变量
    var global_person_name = $('#person_name').val();
    var global_person_nickname = $('#person_nickname').val();
    var global_person_phone = $('#person_phone').val();
    var global_person_old_password = $('#person_old_password').val();
    var global_person_new_password = $('#person_new_password').val();
    var global_person_description = $('#person_description');

    //提示显示时间
    var tips_show_time = 3500;

    //禁止表单自动提交
    $('#person_form').submit(function (e) {
        e.preventDefault();
    });

    $('#person_reset_form').submit(function (e) {
        e.preventDefault();
    });

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
     * 真实姓名验证
     */
    $('#person_name').blur(function () {
        var person_name = $(this).val();
        var re = /^[\u4e00-\u9fa5]+(·[\u4e00-\u9fa5]+)*$/;      //正则，汉字验证
        if (!re.test(person_name)){
            $('#person_name').val('');
            $('#person_tips').val('请输入合法的真实姓名').show().fadeOut(tips_show_time);
        }else {
            global_person_name = person_name;
        }
    });

    /**
     * 昵称查重验证
     */
    $('#person_nickname').blur(function () {
        var person_nickname = $(this).val();
        if (person_nickname.length >= 10){
            $('#person_tips').val('昵称不允许超过10个字符').show().fadeOut(tips_show_time);
        }else {
            $.ajax({
                url:'validate_nickname',
                type:"POST",
                dataType:'json',
                success:function (data) {
                    if (data.error_code == 0){
                        global_person_nickname = person_nickname;
                    } else {
                        $('#person_tips').val('昵称已经存在').show().fadeOut(tips_show_time);
                    }
                },
                error:function (error) {
                    $('#person_tips').val('网络异常').show().fadeOut(tips_show_time);
                }
            });
        }
    })

    /**
     * 手机号查重
     */
    $('#person_phone').blur(function () {
        var person_phone = $(this).val();
        var reg_phone = /^1[34578]\d{9}$/;
        if (!reg_phone.test(person_phone)){
            $('#person_tips').val('请输入正确的手机号').show().fadeOut(tips_show_time);
        }else {
            $.ajax({
                url:'validate_phone',
                type:"POST",
                dataType:'json',
                success:function (data) {
                    if (data.error_code == 0){
                        global_person_phone = person_phone;
                    } else {
                        $('#person_tips').val('手机号已经存在').show().fadeOut(tips_show_time);
                    }
                },
                error:function (error) {
                    $('#person_tips').val('网络异常').show().fadeOut(tips_show_time);
                }
            });
        }
    })

    //获取
    $("#person_btn").click(function () {
        if (global_person_name && global_person_nickname && global_person_phone){
            $.ajax({
                url:'save_info',
                data:
                    {
                        'name':global_person_name,
                        'nickname':global_person_nickname,
                        'phone':global_person_phone,
                        'description':$('#person_description').val(),
                        'user_id':$('#id').val(),
                    },
                type:'POST',
                dataType:'json',
                success:function (data) {
                    $('#person_form').attr('onsubmit',true);
                    if (data.error_code == 0){
                        $('#person_tips').css('color','green').val('信息更新成功').show().fadeOut(tips_show_time);
                    }else {
                        $('#person_tips').val('更新失败').show().fadeOut(tips_show_time);
                    }
                },
                error:function (error) {
                    $('#person_tips').val('网络异常').show().fadeOut(tips_show_time);
                }
            })
        }else {
            $('#person_tips').val('请填写必要信息').show().fadeOut(tips_show_time);
        }
    });

    /**
     * 如果姓名已经填写，则设为只读模式
     */
    if(global_person_name){
        $('#person_name').attr('readOnly',true);
    }

    /**
     *点击头像打开上传框
     */
    $('#person_image').click(function () {
        $('#person_image_upload').click();
    });

    /**
     * 旧密码验证
     */
    $('#person_old_password').blur(function () {
        var password = $('#person_old_password').val();
        var res = checkPassword(password);
        if (res){
            global_person_old_password = password;
        }else {
            $('#person_old_password').val('');
            $('#person_reset_tips').val('旧密码必须由6-12个大小写字母或数字组成').show().fadeOut(tips_show_time);
        }
    });

    /**
     * 新密码验证
     */
    $('#person_new_password').blur(function () {
        var password = $('#person_new_password').val();
        var res = checkPassword(password);
        if (res){
            global_person_new_password = password;
        }else {
            $('#person_new_password').val('');
            $('#person_reset_tips').val('新密码必须由6-12个大小写字母或数字组成').show().fadeOut(tips_show_time);
        }
    });

    $('#person_reset_btn').click(function () {
        if (global_person_old_password && global_person_new_password){
            $.ajax({
                url:'reset_password',
                type:"POST",
                dataType:"json",
                data:{
                  "user_id":$('#id').val(),
                  "old_password":global_person_old_password,
                  "new_password":global_person_new_password,
                },
                success:function (data) {
                    if (data.error_code == 0){
                        $('#jump_to').val(data.rdata.jump_to);
                        $('#person_reset_tips').css('color','green').val('密码更新成功！3秒后跳转至个人资料页').show().fadeOut(tips_show_time);
                        window.setTimeout(goto_article_list,3000);
                    }else {
                        $('#person_reset_tips').val('密码更新失败！').show().fadeOut(tips_show_time);
                        console.log(data.error_msg);
                    }
                },
                error:function (error) {
                    $('#person_reset_tips').val('网络错误').show().fadeOut(tips_show_time);
                }
            });
        }
    });

});

function goto_article_list() {
    window.location.href = $('#jump_to').val();
}
/**
 *上传图片
 */
function upload_image() {
    var forData = new FormData();
    forData.append('file',$('#person_image_upload')[0].files[0]);
    forData.append('user_id',$('#id').val());
    $.ajax({
        url:'update_image',
        type:'POST',
        data:forData,
        cache:false,
        processData:false,
        contentType:false,
        success:function (data) {
            console.log('请求成功');
            if (data.error_code == 0){
                $('#person_image').prop('src',data.data);
                window.location.reload();
            } else {
                alert('图片上传失败：'+ data.error_msg);
            }
        },
        error:function (error) {
            console.log('请求失败');
        }
    })
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
