//禁止表单自动提交
$('#person_form').submit(function (e) {
    e.preventDefault();
});