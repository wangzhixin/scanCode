$(function () {
    function close() {
        $(".choose").hide();
        $(".choose .select").slideUp();
        $("#choose-title").html("");
        $("#listDiv").html("");
    }
    $(".choose .close").click(function () {
        close();
    });
    $(".choose").click(function () {
        close();
    });
    var idList = {
        title: "证件类型 ID Type",
        list: [
            { type: 1, msg: "身份证 Identity Card" },
            { type: 2, msg: "港澳台通行证 Mainland Travel Permit for HongKong/Macao/Taiwan Residents" },
            { type: 3, msg: "护照 Passport" },
        ]
    };
    $("#id").click(function () {
        $("#choose-title").html(idList.title);
        $("#listDiv").html("");
        var insrtHtml = '';
        var chooseIdValue = $("#id_type").val();
        idList.list.forEach(each => {
            var addClass = "";
            if (chooseIdValue == each.type) {
                addClass = "chooseText";
            }
            insrtHtml += '<div class="list ' + addClass + '" data-name="id_type" data-value="' + each.type + '" data-msg="' + each.msg + '">' + each.msg + '</div>';
        })
        $("#listDiv").html(insrtHtml);
        $(".choose").show();
        $(".choose .select").slideDown();
    });
    $(".select").on("click", ".list", function () {
        var thisName = $(this).attr("data-name");
        var thisValue = $(this).attr("data-value");
        var thisMsg = $(this).attr("data-msg");
        $("#" + thisName).val(thisValue);
        $("." + thisName).html(thisMsg);
        $(".choose .select .listDiv .list").removeClass("chooseText");
        $(this).addClass('chooseText');
        $(".choose").hide();
    });
    function alerts(msg) {
        $("#alertMsg").html(msg);
        $(".alert").show();
        setTimeout(() => {
            $(".alert").fadeOut();
        }, 2000);
    }
    var province = {
        title: "省 Province",
        list: []
    };
    var city = {
        title: "现住城市 City",
        list: []
    };
    var district = {
        title: "现住区域 District",
        list: []
    };
    getAreaList(province, 0);
    function getAreaList(name, parentId) {
        var url = $("#apiUrl").val() + "getAreaList.html";
        province.list = [];
        $.ajax({
            url: url,
            dataType: "json",
            type: "post",
            async: false,
            data: { "parent_area_id": parentId },
            success: function (res) {
                if (res.code == 1) {
                    res.list.forEach(each => {
                        name.list.splice(name.list.length, 0, { type: each.area_id, msg: each.area_name });
                    });
                    console.log(name);
                }
            }
        });
    }
    $("#rovince").click(function () {
        $("#choose-title").html(province.title);
        $("#listDiv").html("");
        var insrtHtml = '';
        var chooseIdValue = $("#province_value").val();
        province.list.forEach(each => {
            var addClass = "";
            if (chooseIdValue == each.type) {
                addClass = "chooseText";
            }
            insrtHtml += '<div class="list ' + addClass + '" data-name="province_value" data-value="' + each.type + '" data-msg="' + each.msg + '">' + each.msg + '</div>';
        })
        $("#listDiv").html(insrtHtml);
        $(".choose").show();
        $(".choose .select").slideDown();
    });
    $("#city").click(function () {
        var province_value = $("#province_value").val();
        if (!province_value) {
            alerts('请选择省份');
        } else {
            getAreaList(city, province_value);
            $("#choose-title").html(city.title);
            $("#listDiv").html("");
            var insrtHtml = '';
            var chooseIdValue = $("#city_value").val();
            city.list.forEach(each => {
                var addClass = "";
                if (chooseIdValue == each.type) {
                    addClass = "chooseText";
                }
                insrtHtml += '<div class="list ' + addClass + '" data-name="city_value" data-value="' + each.type + '" data-msg="' + each.msg + '">' + each.msg + '</div>';
            })
            $("#listDiv").html(insrtHtml);
            $(".choose").show();
            $(".choose .select").slideDown();
        }
    });
    $("#submit").click(function () {
        var name = $("#name").val(); if (name == "") { alerts('请输入姓名'); return false };
        var id_type = $("#id_type").val(); if (id_type == "") { alerts('请选择类型'); return false };
        var id_number = $("#id_number").val(); if (id_number == "") { alerts('请输入证件号码'); return false };
        var phone_number = $("#phone_number").val(); if (phone_number == "") { alerts('请输联系电话'); return false };
        var province_value = $("#province_value").val(); if (province_value == "") { alerts('请选择省份'); return false };
        console.log(11);
    });

});