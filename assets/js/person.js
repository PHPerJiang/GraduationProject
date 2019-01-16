$(document).ready(function () {
    //声明全局变量
    var global_person_name;
    var global_person_nickname;
    var global_person_phone;
    var global_person_description;

    //禁止表单自动提交
    $('#person_form').submit(function (e) {
        e.preventDefault();
    });

    //ajax退出登录
    $('#ajax_logout').click(function () {
        var url = $('#ajax_logout').attr('attr-href');
        $.ajax({
            url:url,
            type:"POST",
            dataType:'json',
            success:function (data) {
                if (data.error_code == 0){
                    alert(1);
                }
            },
        });
    });

    $('#person_name').blur(function () {
        var person_name = $(this).val();
        var re = /^[\u4e00-\u9fa5]+(·[\u4e00-\u9fa5]+)*$/;
        if (re.test(person_name)){
            $(this).html('<span style="color: red">请输入合法的姓名</span>>');
        }
    });
});
