$(document).ready(function () {
    //声明全局变量
    var global_person_name;
    var global_person_nickname;
    var global_person_phone;
    var global_person_description;

    //提示显示时间
    var tips_show_time = 3500;

    //禁止表单自动提交
    $('#person_form').submit(function (e) {
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
                },
                error:function (error) {
                    $('#person_tips').val('网络异常').show().fadeOut(tips_show_time);
                }
            })
        }else {
            $('#person_tips').val('请填写必要信息').show().fadeOut(tips_show_time);
        }
    })
});
