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
        if (thisName == 'province_value') {
            $("#city_value").val("");
            $(".city_value").html("");
            $("#district_value").val("");
            $(".district_value").html("");
        }
        if (thisName == 'hospital_value') {
            $("#department_value").val("");
            $(".department_value").html("");
        }
    });
    function alerts(msg) {
        $("#alertMsg").html(msg);
        $(".alert").show();
        setTimeout(() => {
            $(".alert").fadeOut();
        }, 2000);
    }
    var province = {
        type: 1,
        title: "省 Province",
        list: []
    };
    var city = {
        type: 2,
        title: "现住城市 City",
        list: []
    };
    var district = {
        type: 3,
        title: "现住区域 District",
        list: []
    };
    function getAreaList(name, parentId) {
        var url = $("#apiUrl").val() + "getAreaList.html";
        name.list = [];
        $.ajax({
            url: url,
            dataType: "json",
            type: "post",
            async: true,
            data: { "parent_area_id": parentId },
            success: function (res) {
                if (res.code == 1) {
                    res.list.forEach(each => {
                        name.list.splice(name.list.length, 0, { type: each.area_id, msg: each.area_name });
                    });
                    initAreaList(name);
                }
            }
        });
    }
    $("#rovince").click(function () {
        initSelect(province);
        getAreaList(province, 0);
    });
    $("#city").click(function () {
        var province_value = $("#province_value").val();
        if (!province_value) {
            alerts('请选择省份');
        } else {
            initSelect(city);
            getAreaList(city, province_value);
        }
    });
    $("#district").click(function () {
        var city_value = $("#city_value").val();
        if (!city_value) {
            alerts('请选择城市');
        } else {
            initSelect(district);
            getAreaList(district, city_value);
        }
    });
    function initSelect(name) {
        $("#listDiv").addClass("loadingDiv");
        $("#choose-title").html(name.title);
        $("#listDiv").html("");
        $(".choose").show();
        $(".choose .select").slideDown();
    }
    function initAreaHtml(id, name) {
        var insrtHtml = '';
        var chooseIdValue = $("#" + id).val();
        name.list.forEach(each => {
            var addClass = "";
            var dataValue = each.type;
            var dataMsg = each.msg;
            if (chooseIdValue == each.type) {
                addClass = "chooseText";
            }
            insrtHtml += '<div class="list ' + addClass + '" data-name="' + id + '" data-value="' + dataValue + '" data-msg="' + dataMsg + '">' + dataMsg + '</div>';
        })
        $("#listDiv").html(insrtHtml);
        $("#listDiv").removeClass("loadingDiv");
    }
    function initAreaList(name) {
        if (name.type == 1) {
            initAreaHtml('province_value', name);
        }
        if (name.type == 2) {
            initAreaHtml('city_value', name);
        }
        if (name.type == 3) {
            initAreaHtml('district_value', name);
        }
        if (name.type == 4) {
            initAreaHtml('hospital_value', name);
        }
        if (name.type == 5) {
            initAreaHtml('department_value', name);
        }
    }
    var hospital = {
        type: 4,
        title: "就诊医院 Visiting Hospital",
        list: []
    };
    var department = {
        type: 5,
        title: "就诊科室 Visiting department",
        list: []
    };
    function getHospitalList(name) {
        var url = $("#apiUrl").val() + "getHospitalList.html";
        name.list = [];
        $.ajax({
            url: url,
            dataType: "json",
            type: "post",
            async: true,
            success: function (res) {
                if (res.code == 1) {
                    res.list.forEach(each => {
                        name.list.splice(name.list.length, 0, { type: each.hospital_id, msg: each.hospital_name });
                    });
                    initAreaList(name);
                }
            }
        });
    }
    $("#hospital").click(function () {
        initSelect(hospital);
        getHospitalList(hospital);
    });
    function getDepartment(name, hospital_id) {
        var url = $("#apiUrl").val() + "getDepartment.html";
        name.list = [];
        $.ajax({
            url: url,
            dataType: "json",
            type: "post",
            async: true,
            data: { "hospital_id": hospital_id },
            success: function (res) {
                if (res.code == 1) {
                    res.list.forEach(each => {
                        name.list.splice(name.list.length, 0, { type: each.department_id, msg: each.department_name });
                    });
                    initAreaList(name);
                }
            }
        });
    }
    $("#department").click(function () {
        var hospital_value = $("#hospital_value").val();
        if (!hospital_value) {
            alerts('请选择就诊医院');
        } else {
            initSelect(department);
            getDepartment(department, hospital_value);
        }
    });
    $("#chooseFalse").click(function () {
        var v_false = $(".v_false");
        for (let index = 0; index < v_false.length; index++) {
            v_false.eq(index).attr("checked", true);
        }
    });
    $(".video_choose").click(function () {
        var name = $(this).children("input").attr('name');
        $('input[name="' + name + '"]').attr("checked", false);
        $(this).children("input").attr("checked", true);
    });
    $("#submit").click(function () {
        var name = $("#name").val(); if (name == "") { alerts('请输入姓名'); return false };
        var id_type = $("#id_type").val(); if (id_type == "") { alerts('请选择类型'); return false };
        var id_number = $("#id_number").val(); if (id_number == "") { alerts('请输入证件号码'); return false };
        var phone_number = $("#phone_number").val(); if (phone_number == "") { alerts('请输联系电话'); return false };
        var province_value = $("#province_value").val(); if (province_value == "") { alerts('请选择省份'); return false };
        var city_value = $("#city_value").val(); if (city_value == "") { alerts('请选择城市'); return false };
        var district_value = $("#district_value").val(); if (district_value == "") { alerts('请选择区域'); return false };
        var address = $("#address").val(); if (address == "") { alerts('请填写现住址'); return false };
        var hospital_value = $("#hospital_value").val(); if (hospital_value == "") { alerts('请选择就诊医院'); return false };
        var department_value = $("#department_value").val(); if (department_value == "") { alerts('请选择就诊科室'); return false };

        var allRadio = true;
        var problemList = [];
        var v_false = $(".v_false");
        for (let index = 0; index < v_false.length; index++) {
            var eachValue = $('input:radio[name="' + v_false.eq(index).attr('name') + '"]:checked').val();
            problemList.splice(problemList.length, 0, { id: v_false.eq(index).attr('name'), value: eachValue });
            if (eachValue == undefined) {
                allRadio = false;
            }
        }
        if (allRadio == false) {
            alerts('您还有问题没有回答'); return false;
        }
        var remind = $("#remind").get(0).checked;
        if (remind == false) {
            alerts('请认真阅读并勾选阅读提示'); return false;
        } else {
            var postData = {
                name: name,
                id_type: id_type,
                id_number: id_number,
                phone_number: phone_number,
                province_value: province_value,
                city_value: city_value,
                district_value: district_value,
                address: address,
                hospital_value: hospital_value,
                department_value: department_value,
                problemList: problemList,
                peopleType: $("#peopleType").val()
            };
            $(".loading").show();
            var url = $("#apiUrl").val() + "submitContent.html";
            $.ajax({
                url: url,
                dataType: "json",
                type: "post",
                async: true,
                data: { "postData": postData },
                success: function (res) {
                    window.location.href = "/index.php/index/index/show.html";
                }
            });

        }
    });

});