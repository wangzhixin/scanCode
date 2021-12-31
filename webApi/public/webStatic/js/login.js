$(function () {
    $(".submit").click(function () {
        var account = $("#account").val(); if (account == "") { alerts('请输入帐号'); return false };
        var password = $("#password").val(); if (password == "") { alerts('请输入密码'); return false };
        var url = $("#apiUrl").val() + "ajaxLogin.html";
        loading();
        $.ajax({
            url: url,
            dataType: "json",
            type: "post",
            async: true,
            data: { "account": account, "password": password },
            success: function (res) {
                loadingHide();
                if (res.code == 1) {
                    var id = $("#id").val();
                    window.location.href = "/index.php/index/admin/index/id/" + id + ".html";
                }else{
                    alerts(res.msg);
                }
            }
        });
    });
    function loading() {
        $(".loading").show();
    }
    function loadingHide() {
        $(".loading").hide();
    }
    function alerts(msg) {
        $("#alertMsg").html(msg);
        $(".alert").show();
        setTimeout(() => {
            $(".alert").fadeOut();
        }, 2000);
    }
})