<?php

declare(strict_types=1);

namespace app\index\controller;

use think\facade\Db;
use think\facade\View;
use think\facade\Request;
use think\facade\Session;

class Admin
{
    public function index()
    {
        $id = Request::param('id', '');
        $adminUserId = Session::get('adminUserId');
        if ($adminUserId) {
            if ($id) {
                $data['logo'] = '';
                $find = Db::table('setting')->order('id', 'desc')->find();
                if ($find) {
                    $data = $find;
                }
                View::assign('data', $data);

                $userData = Db::table('user_data')->where(['data_id' => $id])->find();
                $userData['id_type_text'] = config('idtype.list')[$userData['id_type']];
                $userData['invalid_time_text'] = "已到期";
                if($userData['invalid_time']<time()){
                    $userData['invalid_time_text'] = date("Y-m-d H:i:s");
                }
                View::assign('userData', $userData);

                $status = 1;
                $problemList = [];
                foreach (json_decode($userData['problemList'], true) as $each) {
                    $choose = '否';
                    if ($each['value'] == 1) {
                        $choose = '是';
                        $status = 2;
                    }
                    $problemList[] = [
                        'title' => Db::table('problem')->where(['problem_id' => explode('v_', $each['id'])[1]])->value('problem'),
                        'choose' => $choose,
                        'value' => $each['value'],
                    ];
                }
                View::assign('problemList', $problemList);
                View::assign('status', $status);
                return view();
            } else {
                throw new \think\exception\HttpException(404, '404');
            }
        } else {
            return redirect((string)url("admin/login", array('id' => $id)));
        }
    }
    public function login()
    {
        $id = Request::param('id', '');
        $data['logo'] = '';
        $find = Db::table('setting')->order('id', 'desc')->find();
        if ($find) {
            $data = $find;
        }
        View::assign('data', $data);
        View::assign('id', $id);
        View::assign('apiUrl', getConfig("api.config.web_url") . "/index.php/index/");
        return view();
    }
    public function ajaxLogin()
    {
        $result = array("code" => 0, 'msg' => '帐号或密码错误');
        $account = Request::post('account', '');
        $password = Request::post('password', '');
        if ($account && $password) {
            $find = Db::table('admin_users')->where(['admin_name' => $account])->find();
            $pass = getPasswordMd5Admin($password);
            if ($find['admin_password'] == $pass) {
                $result = array("code" => 1, 'msg' => 'ok');
                Session::set('adminUserId', $find['admin_id']);
            }
        }
        return json($result);
    }
}
