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
    /*
    功能：验证身份证号码是否有效
    提 示信息：未输入或输入身份证号不正确！
    使用：isIdCard(obj)
    返回：0,1,2,3
    返回0表示身份证号码正确
    返回1表示非法身份证号码
    返回2表示分发地区
    返回3表示非法生日
*/
    function isIdCard(obj) {
        var aCity = { 11: "北京", 12: "天津", 13: "河北", 14: "山西", 15: "内蒙古", 21: "辽宁", 22: "吉林", 23: "黑龙 江", 31: "上海", 32: "江苏", 33: "浙江", 34: "安徽", 35: "福建", 36: "江西", 37: "山东", 41: "河南", 42: "湖 北", 43: "湖南", 44: "广东", 45: "广西", 46: "海南", 50: "重庆", 51: "四川", 52: "贵州", 53: "云南", 54: "西 藏", 61: "陕西", 62: "甘肃", 63: "青海", 64: "宁夏", 65: "新疆", 71: "台湾", 81: "香港", 82: "澳门", 91: "国 外" };
        var iSum = 0;
        var strIDno = obj;
        var idCardLength = strIDno.length;
        if (!/^\d{17}(\d|x)$/i.test(strIDno) && !/^\d{15}$/i.test(strIDno))
            return 1; //非法身份证号

        if (aCity[parseInt(strIDno.substr(0, 2))] == null)
            return 2;// 非法地区
        // 15位身份证转换为18位
        if (idCardLength == 15) {
            sBirthday = "19" + strIDno.substr(6, 2) + "-" + Number(strIDno.substr(8, 2)) + "-" + Number(strIDno.substr(10, 2));
            var d = new Date(sBirthday.replace(/-/g, "/"))
            var dd = d.getFullYear().toString() + "-" + (d.getMonth() + 1) + "-" + d.getDate();
            if (sBirthday != dd)
                return 3; //非法生日
            strIDno = strIDno.substring(0, 6) + "19" + strIDno.substring(6, 15);
            strIDno = strIDno + GetVerifyBit(strIDno);
        }

        // 判断是否大于2078年，小于1900年
        var year = strIDno.substring(6, 10);
        if (year < 1900 || year > 2078)
            return 3;//非法生日

        //18位身份证处理
        //在后面的运算中x相当于数字10,所以转换成a
        strIDno = strIDno.replace(/x$/i, "a");
        sBirthday = strIDno.substr(6, 4) + "-" + Number(strIDno.substr(10, 2)) + "-" + Number(strIDno.substr(12, 2));
        var d = new Date(sBirthday.replace(/-/g, "/"))
        if (sBirthday != (d.getFullYear() + "-" + (d.getMonth() + 1) + "-" + d.getDate()))
            return 3; //非法生日
        // 身份证编码规范验证
        for (var i = 17; i >= 0; i--)
            iSum += (Math.pow(2, i) % 11) * parseInt(strIDno.charAt(17 - i), 11);
        if (iSum % 11 != 1)
            return 1;// 非法身份证号

        // 判断是否屏蔽身份证
        var words = new Array();
        words = new Array("11111119111111111", "12121219121212121");

        for (var k = 0; k < words.length; k++) {
            if (strIDno.indexOf(words[k]) != -1) {
                return 1;
            }
        }

        return 0;
    }
    function checkHKMacao(code) {
        var tip = 1;
        if (!code || !/^[HMhm]{1}([0-9]{10}|[0-9]{8})$/.test(code)) {
            tip = 0;
        }
        return tip;
    }
    function checkPassport(code) {
        var tip = 1;
        if (!code || !/^((1[45]\d{7})|(G\d{8})|(P\d{7})|(S\d{7,8}))?$/.test(code)) {
            tip = 0;
        }
        return tip;
    }
    function checkPhone(phone) {
        var tip = true;
        if (!(/^1(3|4|5|6|7|8|9)\d{9}$/.test(phone))) {
            tip = false;
        }
        return tip;
    }
    $("#submit").click(function () {
        var name = $("#name").val(); if (name == "") { alerts('请输入姓名'); return false };
        var id_type = $("#id_type").val(); if (id_type == "") { alerts('请选择类型'); return false };
        var id_number = $("#id_number").val(); if (id_number == "") { alerts('请输入证件号码'); return false };
        var phone_number = $("#phone_number").val(); if (phone_number == "") { alerts('请输联系电话'); return false };
        if (checkPhone(phone_number) == false) {
            alerts('请输正确的手机号码'); return false
        }
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
        switch (id_type) {
            case '1':
                if (isIdCard(id_number) != 0) {
                    alerts('身份证号码不正确'); return false;
                }
                break;
            case '2':
                if (checkHKMacao(id_number) == false) {
                    alerts('港澳台通行证号码不正确'); return false;
                }
                break;
            case '3':
                if (checkPassport(id_number) == false) {
                    alerts('护照号码不正确'); return false;
                }
                break;

            default:
                alerts('请选择类型'); return false;
                break;
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
                    $(".loading").hide();
                    window.location.href = "/index.php/index/index/show.html";
                }
            });

        }
    });

});