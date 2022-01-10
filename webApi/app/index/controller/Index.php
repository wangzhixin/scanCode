<?php

declare(strict_types=1);

namespace app\index\controller;

use think\facade\Db;
use think\facade\View;
use think\facade\Request;
use think\facade\Session;
use app\provider\ErCode;

class Index
{
    public $userId;
    public function __construct()
    {
        $this->userId = Session::get('userId');
    }

    public function index()
    {
        $code = Request::param('code', '');
        if (is_weixin()) {
            if (!$code) {
                $this->weixinCode();
            } else {
                $this->getUser($code);
                // Session::set('userId', 1);
                // if (!$this->userId) {
                //     $open_id = get_guid();
                //     $userId = Db::table('user')->insertGetId(['open_id' => $open_id]);
                //     Session::set('userId', $userId);
                // }
                $data['logo'] = '';
                $data['title'] = '';
                $data['title_en'] = '';
                $find = Db::table('setting')->order('id', 'desc')->find();
                if ($find) {
                    $data = $find;
                }
                View::assign('data', $data);
                return view();
            }
        } else {
            echo "请在微信环境打开~";
        }
    }
    private function getUser($code)
    {
        Db::startTrans();
        try {
            $userId = "";
            $data = array();
            $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . config('config.appid') . '&secret=' . config('config.appsecret') . '&code=' . $code . '&grant_type=authorization_code';
            $str = get_url($url);
            $arr = json_decode($str, true);
            $data['open_id'] = $arr['openid'];
            $url = 'https://api.weixin.qq.com/sns/userinfo?access_token=' . $arr['access_token'] . '&openid=' . $arr['openid'];
            $getUserName = json_decode(get_url($url), true);
            $data['nick_name'] = $getUserName['nickname'];
            $data['header_url'] = $getUserName['headimgurl'];
            $user = Db::table('user')->where(['open_id' => $arr['openid']])->find();
            if ($user) {
                $userId = $user['user_id'];
            } else {
                $userId = Db::table('user')->insertGetId($data);
            }
            Db::commit();
            Session::set('userId', $userId);
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            getErrorMessage($e);
            echo "系统错误，请稍后~";
            exit;
        }
    }
    private function weixinCode()
    {
        $baseUrl = urlencode(config('config.web_url') . "/index.php/index/index/index.html");
        $urlObj["appid"] = config('config.appid');
        $urlObj["redirect_uri"] = $baseUrl;
        $urlObj["response_type"] = 'code';
        $urlObj["scope"] = "snsapi_userinfo"; //snsapi_base
        $urlObj["state"] = "STATE" . "#wechat_redirect";
        $bizString = ToUrlParams($urlObj);
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?" . $bizString;
        Header("Location: $url");
        exit();
    }
    public function add()
    {
        $type = Request::param('type', 1);
        if ($this->userId) {
            $problemList = [];
            $data = Db::table('user_data')->where(['user_id' => $this->userId, 'type' => $type])->order('data_id', 'desc')->find();
            if ($data) {
                $data['province_value_id'] = Db::table('area_list')->where(['area_name' => $data['province_value']])->value("area_id");
                $data['city_value_id'] = Db::table('area_list')->where(['area_name' => $data['city_value']])->value("area_id");
                $data['district_value_id'] = Db::table('area_list')->where(['area_name' => $data['district_value']])->value("area_id");
                foreach (json_decode($data['problemList'], true) as $each) {
                    $id = explode('v_', $each['id'])[1];
                    $problemList[$id] = $each['value'];
                }
                View::assign('problemList', $problemList);
            }
            $problem = Db::table("problem")->where(['is_deleted' => 0])->select()->toArray();
            foreach ($problem as &$eachProblem) {
                $eachProblem['checked'] = array('', '');
                if (count($problemList) > 0 && isset($problemList[$eachProblem['problem_id']])) {
                    if ($problemList[$eachProblem['problem_id']] == 1) {
                        $eachProblem['checked'][0] = 'checked';
                    } else {
                        $eachProblem['checked'][1] = 'checked';
                    }
                }
            }
            $idType = config('idtype.list');
            View::assign('problem', $problem);
            View::assign('apiUrl', getConfig("api.config.web_url") . "/index.php/index/");
            View::assign('data', $data);
            View::assign('idType', $idType);
            View::assign('type', $type);
        } else {
            return redirect((string)url("index/index"));
        }
        return view();
    }
    public function show()
    {
        $userId = $this->userId;
        $is_index = false;
        if (!$userId) {
            $is_index = true;
        }
        $data = array();
        $find = Db::table('setting')->order('id', 'desc')->find();
        if ($find) {
            $data['logo'] = $find['logo'];
        }
        $user = Db::table('user')->where(['user_id' => $userId])->find();
        $user_data = Db::table('user_data')->where(['user_id' => $userId])->order('data_id', 'desc')->find();
        if (!$user || !$user_data) {
            $is_index = true;
        } else {
            $data['name'] = $user_data['name'];
            $data['phone_number'] = mobileReplace($user_data['phone_number']);
            $data['id_number'] = hiddenNickname($user_data['id_number']);
            $data['er_code'] = config("config.web_url") . $user_data['er_code'];
            $data['add_time'] = $user['add_time'];
            $data['invalid_time'] = date("Y-m-d H:i:s", $user_data['invalid_time']);
            $data['type'] = $user_data['type'];
        }
        if ($is_index) {
            return redirect((string)url("index/index"));
        }
        View::assign('data', $data);
        return view();
    }
    public function getAreaList()
    {
        $result = array("code" => 0, 'list' => []);
        if ($this->userId) {
            $parent_area_id = Request::post('parent_area_id', 0);
            $list = Db::table('area_list')->where(["parent_area_id" => $parent_area_id])->select();
            $result = array("code" => 1, 'list' => $list);
        }
        return json($result);
    }
    public function getHospitalList()
    {
        $result = array("code" => 0, 'list' => []);
        if ($this->userId) {
            $list = Db::table('hospital')->select();
            $result = array("code" => 1, 'list' => $list);
        }
        return json($result);
    }
    public function getDepartment()
    {
        $result = array("code" => 0, 'list' => []);
        if ($this->userId) {
            $hospital_id = Request::post('hospital_id', 0);
            $list = Db::table('department')->where(["hospital_id" => $hospital_id])->select();
            $result = array("code" => 1, 'list' => $list);
        }
        return json($result);
    }
    public function submitContent(ErCode $ercode)
    {
        $result = array("code" => 0, 'list' => []);
        if ($this->userId) {
            $userId = Session::get('userId');
            // echo $er_code_url;exit;
            $postData = Request::post('postData', "");
            if ($postData && $userId) {
                $data['user_id'] = $userId;
                $data['name'] = $postData['name'];
                $data['id_type'] = $postData['id_type'];
                $data['id_number'] = $postData['id_number'];
                $data['phone_number'] = $postData['phone_number'];
                $data['province_value'] = Db::table('area_list')->where(['area_id' => $postData['province_value']])->value("area_name");
                $data['city_value'] = Db::table('area_list')->where(['area_id' => $postData['city_value']])->value("area_name");
                $data['district_value'] = Db::table('area_list')->where(['area_id' => $postData['district_value']])->value("area_name");
                $data['address'] = $postData['address'];
                $data['hospital_value'] = Db::table('hospital')->where(['hospital_id' => $postData['hospital_value']])->value("hospital_name");
                $data['department_value'] = Db::table('department')->where(['department_id' => $postData['department_value']])->value("department_name");
                $data['hospital_id'] = $postData['hospital_value'];
                $data['department_id'] = $postData['department_value'];
                $data['problemList'] = json_encode($postData['problemList']);
                $data['invalid_time'] = time() + 3600 * 24;
                $data['type'] = $postData['peopleType'];
                $type = 1;
                foreach ($postData['problemList'] as $each) {
                    if ($each['value'] > 0) {
                        $type = 2;
                    }
                }
                $data['type'] = $type;
                $erColor = ['r' => 6, 'g' => 163, 'b' => 6, 'a' => 0];
                if ($type == 2) {
                    $erColor = ['r' => 217, 'g' => 217, 'b' => 27, 'a' => 0];
                }
                Db::startTrans();
                try {

                    $dataId = Db::table("user_data")->insertGetId($data);

                    $er_code_url = getConfig('api.config.web_url') . (string)url("admin/index", ['id' => $dataId]);
                    Db::table("user_data")->where(['data_id' => $dataId])->update(['er_code' => $ercode->getCode($er_code_url, $erColor)]);

                    Db::commit();
                } catch (\Exception $e) {
                    Db::rollback();
                    getErrorMessage($e);
                }
            }
            $result = array("code" => 1, 'data' => 'ok');
        }
        return json($result);
    }
}
