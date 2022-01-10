<?php

// 应用公共文件
function jsonEncode($array)
{
    return json_encode($array);
}
/**
 * @desc post提交
 * @date 2020-07-16
 */
function postData($url, $data, $headers = array())
{
    $ch = curl_init();
    $timeout = 300;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    if (count($headers) > 0) {
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }
    $handles = curl_exec($ch);
    curl_close($ch);
    $handles = trim($handles, chr(239) . chr(187) . chr(191) . PHP_EOL);
    return $handles;
}
/**
 * @desc post提交 json raw方式
 * @date 2020-08-07
 */
function http_post($url, $data_string)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'X-AjaxPro-Method:ShowList',
        'Content-Type: application/json; charset=utf-8',
    ));
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}
/**
 * @desc get提交
 * @date 2020-07-16
 */
function getUrl($url)
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    $tmpInfo = curl_exec($curl);
    curl_close($curl);
    return $tmpInfo;
}
/**
 * @desc 超过长度用省略号代替
 * @date 2020-07-23
 */
function subText($text, $length)
{
    if (mb_strlen($text, 'utf8') > $length) {
        return mb_substr($text, 0, $length, 'utf8') . '...';
    } else {
        return $text;
    }
}
function get_guid($l = 45)
{
    $charid = strtoupper(md5(uniqid(mt_rand(), true)));
    $hyphen = chr(45); // "-"
    $uuid   = substr($charid, 0, 8) . $hyphen
        . substr($charid, 8, 4) . $hyphen
        . substr($charid, 12, 4) . $hyphen
        . substr($charid, 16, 4) . $hyphen
        . substr($charid, 20, 12);
    return $uuid;
}
/**
 * @desc 生成随机字符串
 * @date 2020-08-04
 */
function getRandChar($length)
{
    $str = null;
    $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
    $max = strlen($strPol) - 1;
    for ($i = 0; $i < $length; $i++) {
        $str .= $strPol[rand(0, $max)];
    }
    return $str;
}

/**
 * 获取客户端IP地址
 * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
 * @date 2020-08-04
 */
function get_client_ip($type = 0)
{
    $type       =  $type ? 1 : 0;
    static $ip  =   NULL;
    if ($ip !== NULL) return $ip[$type];
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $pos    =   array_search('unknown', $arr);
        if (false !== $pos) unset($arr[$pos]);
        $ip     =   trim($arr[0]);
    } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ip     =   $_SERVER['HTTP_CLIENT_IP'];
    } elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip     =   $_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $long = sprintf("%u", ip2long($ip));
    $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
    return $ip[$type];
}
/**
 * @Time    :   2020/10/14 16:00:04
 * @Author  :   wangZhixin 
 * @Desc    :   将阿拉伯小写数组转为大写中文
 */
function numToWord($num)
{
    $chiNum = array('零', '一', '二', '三', '四', '五', '六', '七', '八', '九');
    $chiUni = array('', '十', '百', '千', '万', '亿', '十', '百', '千');

    $chiStr = '';

    $num_str = (string)$num;

    $count = strlen($num_str);
    $last_flag = true; //上一个 是否为0
    $zero_flag = true; //是否第一个
    $temp_num = null; //临时数字

    $chiStr = ''; //拼接结果
    if ($count == 2) { //两位数
        $temp_num = $num_str[0];
        $chiStr = $temp_num == 1 ? $chiUni[1] : $chiNum[$temp_num] . $chiUni[1];
        //当以1开头 都是十一，十二，以十开头的 我们就取$chiUni[i]也就是十当不是以1开头时，而是以2,3,4,我们取这个数字相应的中文并拼接上十
        $temp_num = $num_str[1];
        $chiStr .= $temp_num == 0 ? '' : $chiNum[$temp_num];
        //取得第二个值并的到他的中文
    } else if ($count > 2) {
        $index = 0;
        for ($i = $count - 1; $i >= 0; $i--) {
            $temp_num = $num_str[$i];         //获取的个位数
            if ($temp_num == 0) {
                if (!$zero_flag && !$last_flag) {
                    $chiStr = $chiNum[$temp_num] . $chiStr;
                    $last_flag = true;
                }
            } else {
                $chiStr = $chiNum[$temp_num] . $chiUni[$index % 9] . $chiStr;
                //$index%9 index原始值为0，所以开头为0 后面根据循环得到：0,1,2,3...（不知道为什么直接用$index而是选择$index%9  毕竟两者结果是一样的）
                //当输入的值为：1003 ，防止输出一千零零三的错误情况，$last_flag就起到作用了当翻译倒数第二个值时，将$last_flag设定为true;翻译第三值时在if(!$zero&&!$last_flag)的判断中会将其拦截，从而跳过
                $zero_flag = false;
                $last_flag = false;
            }
            $index++;
        }
    } else {
        $chiStr = $chiNum[$num_str[0]];    //单个数字的7a64e58685e5aeb931333431336230直接取中文
    }
    return $chiStr;
}
/**
 * @Time    :   2020/10/16 14:03:56
 * @Author  :   wangZhixin 
 * @Desc    :   获取错误信息
 */
function getErrorMessage($e)
{
    $msg = think\facade\Request::root() . "-" . think\facade\Request::controller() . "-" . think\facade\Request::action();
    $msg .= '：' . $e->getMessage(); // 获取错误信息
    $msg .= '--文件：' . $e->getFile(); // 异常发生所在文件绝对路径
    $msg .= '--行号：' . $e->getLine(); // 异常发生所在行
    $msg .= '--IP：' . get_client_ip(); // IP地址
    $msg .= '--参数：' . json_encode(think\facade\Request::post(), JSON_UNESCAPED_UNICODE); // 参数
    think\facade\Log::write($msg, 'error');
}
/**
 * @Time    :   2020/10/22 09:25:18
 * @Author  :   wangZhixin 
 * @Desc    :   日志存储
 */
function setLog($msg)
{
    think\facade\Log::debug(think\facade\Request::root() . "-" . think\facade\Request::controller() . "-" . think\facade\Request::action() . ":" . $msg);
}
/**
 * @Time    :   2020/12/22 12:12:21
 * @Author  :   wangZhixin 
 * @Desc    :   错误日志存储
 */
function setLogError($msg)
{
    $post = think\facade\Request::post();
    if ($post && is_array($post)) {
        $msg .= '--参数：' . json_encode($post); // 参数
    }
    think\facade\Log::write(think\facade\Request::root() . "-" . think\facade\Request::controller() . "-" . think\facade\Request::action() . "---" . $msg, 'error');
}
/**
 * @Time    :   2020/10/29 16:11:23
 * @Author  :   wangZhixin 
 * @Desc    :   隐藏手机位数
 */
function mobileReplace($mobile, $start = 4, $end = 7, $str = "*")
{
    $countStr = abs($end - $start);
    $replaceStr = $str;
    for ($i = 0; $i < $countStr; $i++) {
        $replaceStr .= $str;
    }
    $mobile = substr_replace($mobile, $replaceStr, $start - 1, $countStr + 1);
    return $mobile;;
}
/**
 * 二维数组根据某个字段排序
 * @param array $array 要排序的数组
 * @param string $keys   要排序的键字段
 * @param string $sort  排序类型  SORT_ASC     SORT_DESC 
 * @return array 排序后的数组
 */
function arraySort($array, $keys, $sort = SORT_ASC)
{
    $keysValue = [];
    foreach ($array as $k => $v) {
        $keysValue[$k] = $v[$keys];
    }
    array_multisort($keysValue, $sort, $array);
    return $array;
}
/**
 * @Time    :   2020/11/16 19:10:49
 * @Author  :   wangZhixin 
 * $dst_w:目标输出的宽
 * $dst_h:目标输出的高
 * $img_w：原始图片宽
 * $img_h：原始图片高
 */
function thumb($img_w, $img_h, $dst_w, $dst_h)
{
    $src_w = $dst_w;
    $src_h = $dst_h;

    # 等比例缩放
    $scale = ($src_w / $img_w) > ($src_h / $img_h) ? ($src_w / $img_w) : ($src_h / $img_h);

    # 向下取整
    $dst_w = floor($src_w / $scale);
    $dst_h = floor($src_h / $scale);
    return [$dst_w, $dst_h];
}
/**
 * @Time    :   2020/11/19 11:37:05
 * @Author  :   wangZhixin 
 * @Desc    :   返回当前 Unix 时间戳和微秒数(用秒的小数表示)浮点数表示
 */
function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}
/**
 * @Time    :   2021/02/03 16:40:26
 * @Author  :   wangZhixin 
 * @Desc    :   获取毫秒
 */
function microsecond()
{
    $t = explode(" ", microtime());
    $microsecond = round(round($t[1] . substr($t[0], 2, 3)));
    return $microsecond;
}
/**
 * 精确加法
 * @param [type] $a [description]
 * @param [type] $b [description]
 */
function math_add($a, $b, $scale = '2')
{
    return (float)bcadd((string)$a, (string)$b, $scale);
}


/**
 * 精确减法
 * @param [type] $a [description]
 * @param [type] $b [description]
 */
function math_sub($a, $b, $scale = '2')
{
    return (float)bcsub((string)$a, (string)$b, $scale);
}

/**
 * 精确乘法
 * @param [type] $a [description]
 * @param [type] $b [description]
 */
function math_mul($a, $b, $scale = '2')
{
    return (float)bcmul((string)$a, (string)$b, $scale);
}

/**
 * 精确除法
 * @param [type] $a [description]
 * @param [type] $b [description]
 */
function math_div($a, $b, $scale = '2')
{
    return (float)bcdiv((string)$a, (string)$b, $scale);
}

/**
 * 精确求余/取模
 * @param [type] $a [description]
 * @param [type] $b [description]
 */
function math_mod($a, $b)
{
    return (float)bcmod((string)$a, (string)$b);
}
/**
 * @Time    :   2020/12/04 15:18:50
 * @Author  :   wangZhixin 
 * @Desc    :   小数精度计算
 * 使用方法 float_math(3.2,'+',1.5)
 */
function float_math($a, $symbol, $b)
{
    $return = 0;
    if ($symbol == '+') {
        $return = math_add($a, $b);
    }
    if ($symbol == '-') {
        $return = math_sub($a, $b);
    }
    if ($symbol == '*') {
        $return = math_mul($a, $b);
    }
    if ($symbol == '/') {
        $return = math_div($a, $b);
    }
    return $return;
}

/**
 * 比较大小
 * @param [type] $a [description]
 * @param [type] $b [description]
 * 大于 返回 1 等于返回 0 小于返回 -1
 */
function math_comp($a, $b, $scale = '5')
{
    return bccomp((string)$a, (string)$b, $scale); // 比较到小数点位数
}
/**
 * @Time    :   2020/11/29 16:36:01
 * @Author  :   wangZhixin 
 * @Desc    :   精确取
 */
function math_get($a)
{
    if ($a > 0) {
        $a = strval($a * 100);
        $a = intval($a);
        $a = $a / 100;
        return $a;
    } else {
        return 0;
    }
}
/**
 * @Time    :   2021/02/25 15:27:30
 * @Author  :   wangZhixin 
 * @Desc    :   用户昵称隐藏部分
 */
function hiddenNickname($user_name)
{
    $strlen     = mb_strlen($user_name, 'utf-8');
    $firstStr     = mb_substr($user_name, 0, 3, 'utf-8');
    $lastStr     = mb_substr($user_name, -4, 4, 'utf-8');
    return $strlen == 7 ? $firstStr . str_repeat('*', mb_strlen($user_name, 'utf-8') - 1) : $firstStr . str_repeat("*", $strlen - 2) . $lastStr;
}
/**
 * @Time    :   2021/06/25 17:31:31
 * @Author  :   wangZhixin 
 * @Desc    :   跨应用获取配置文件
 * $config = "adminapi.config.adminRoles"
 */
function getConfig($config)
{
    $return = '';
    $configArray = explode('.', $config);
    if (count($configArray) > 2) {
        $file = base_path() . strtolower($configArray[0]) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . strtolower($configArray[1]) . ".php";
        $c = new \think\facade\Config;
        $c::load($file, $configArray[1]);
        unset($configArray[0]);
        $return = $c::get(implode('.', $configArray));
    }
    return $return;
}
/**
 * @Time    :   2021/08/23 16:17:23
 * @Author  :   wangZhixin 
 * @Desc    :   判断字符串是否为json
 * @Return  ： true or false
 */
function is_json($string)
{
    json_decode($string);
    return (json_last_error() == JSON_ERROR_NONE);
}
/**
 * @Time    :   2021/11/02 15:09:58
 * @Author  :   wangZhixin 
 * @Desc    :   时间格式化
 */
function formatLiveTime($live_time)
{
    $text_day = '';
    $d = '';
    $timeDayF = strtotime(date('Y-m-d'));
    $timeDayL = strtotime("+1 Day", strtotime(date('Y-m-d')));
    $timeDayT = strtotime("+1 Day", strtotime(date('Y-m-d')));
    if ($live_time >= $timeDayF && $live_time < $timeDayL) {
        $text_day = "今天";
    } else if ($live_time >= $timeDayL && $live_time < $timeDayT) {
        $text_day = "明天";
    } else {
        $text_day = date("m-d", $live_time);
    }
    $datetime = date('H');
    if ($datetime > 0 && $datetime <= 12) {
        $d = '上午';
    } else {
        $d = '下午';
    }
    return $text_day . " - " . $d . date('h:i', $live_time);
}
/**
 * @Time    :   2021/11/12 11:11:47
 * @Author  :   wangZhixin 
 * @Desc    :   转换数字为简短形式
 * @param $n int 要转换的数字
 * @param $precision int 精度
 */
function shortenNumber($n, $precision = 1)
{
    if ($n < 1e+3) {
        $out = number_format($n);
    } else if ($n < 1e+6) {
        $out = number_format($n / 1e+3, $precision) . 'k';
    } else if ($n < 1e+9) {
        $out = number_format($n / 1e+6, $precision) . 'm';
    } else if ($n < 1e+12) {
        $out = number_format($n / 1e+9, $precision) . 'b';
    }

    return $out;
}
function echoHtml($data, $key)
{
    $return = "";
    if (isset($data[$key])) {
        $return = $data[$key];
    }
    return $return;
}
function getPasswordMd5Admin(string $txt)
{
    return md5(md5($txt . getConfig('api.config.MD5KEYAdmin')));
}
function ToUrlParams($urlObj)
{
    $buff = "";
    foreach ($urlObj as $k => $v) {
        if ($k != "sign") {
            $buff .= $k . "=" . $v . "&";
        }
    }

    $buff = trim($buff, "&");
    return $buff;
}
function is_weixin()
{
    if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
        return true;
    } else {
        return false;
    }
}
function get_url($url, $param = null)
{
    if ($param != null) {
        $query = http_build_query($param);
        $url = $url . '?' . $query;
    }
    $ch = curl_init();
    if (stripos($url, "https://") !== false) {
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    }

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $content = curl_exec($ch);
    $status = curl_getinfo($ch);
    curl_close($ch);
    if (intval($status["http_code"]) == 200) {
        return $content;
    } else {
        echo $status["http_code"];
        return false;
    }
}
