//禁止表单自动提交
$('#person_form').submit(function (e) {
    e.preventDefault();
});

//退出登录
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

