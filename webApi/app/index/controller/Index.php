<?php

declare(strict_types=1);

namespace app\index\controller;

use think\facade\Db;
use think\facade\View;
use think\facade\Request;

class Index
{
    public function index()
    {
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
    public function add()
    {
        $problem = Db::table("problem")->where(['is_deleted' => 0])->select();
        View::assign('problem', $problem);
        View::assign('apiUrl', getConfig("api.config.web_url") . "/index.php/index/");
        return view();
    }
    public function show()
    {
        return view();
    }
    public function getAreaList()
    {
        $parent_area_id = Request::post('parent_area_id', 0);
        $list = Db::table('area_list')->where(["parent_area_id" => $parent_area_id])->select();
        $result = array("code" => 1, 'list' => $list);
        return json($result);
    }
    public function getHospitalList()
    {
        $list = Db::table('hospital')->select();
        $result = array("code" => 1, 'list' => $list);
        return json($result);
    }
    public function getDepartment()
    {
        $hospital_id = Request::post('hospital_id', 0);
        $list = Db::table('department')->where(["hospital_id" => $hospital_id])->select();
        $result = array("code" => 1, 'list' => $list);
        return json($result);
    }
    public function submitContent()
    {
        $postData = Request::post('postData', "");
        if ($postData) {
            $data['user_id'] = 1;
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
            $data['problemList'] = json_encode($postData['problemList']);
            $data['invalid_time'] = time() + 3600 * 24;
            Db::table("user_data")->insert($data);
        }
        $result = array("code" => 1, 'data' => 'ok');
        return json($result);
    }
}
