<?php

namespace app\api\controller;

use app\api\BaseController;
use think\facade\Db;

class User extends BaseController
{
    /**
     * @Time    :   2021/12/29 10:51:10
     * @Author  :   wangZhixin 
     * @Desc    :   登陆
     */
    public function loginAdmin()
    {
        $userName = $this->request->post("username", '');
        $password = $this->request->post("password", '');
        if ($userName && $password) {
            $data = array();
            if ($userName && $password) {
                //管理员登陆
                $token = '';
                $checkPassword = $this->getPasswordMd5Admin($password);
                $where = array();
                $where['admin_name'] = $userName;
                $where['admin_password'] = $checkPassword;
                $where['is_deleted'] = 0;
                $where['admin_type'] = 1;
                $find = Db::table('admin_users')->where($where)->find();
                if ($find) {
                    $data['path'] = '/';
                    $token = array(
                        'userId' => $find['admin_id']
                    );
                }
                if ($find) {
                    $this->setMsg();
                } else {
                    $this->setMsg('用户名或密码错误！');
                }
            }
            if ($token) {
                $data['token'] = $this->jwt->getToken($token);
            }
            if ($data) {
                $this->setData($data);
            }
        } else {
            $this->setMsg('用户名或密码错误！');
        }
        return $this->jsonEncode();
    }
    /**
     * @Time    :   2021/12/29 10:51:49
     * @Author  :   wangZhixin 
     * @Desc    :   获取管理员信息
     */
    public function getUserInfo()
    {
        $data = array();
        $getStore = Db::table('admin_users')->where(['admin_id' => $this->userId])->find();
        if ($getStore) {
            $data['name'] = $getStore['admin_name'];
        }
        if (count($data) > 0) {
            $data['adminUserId'] = config('config.admin_user_id');
            $data['roles'] = [config('config.adminRoles')];
        }
        $this->setData($data);
        return $this->jsonEncode();
    }
    /**
     * @Time    :   2021/12/29 10:52:53
     * @Author  :   wangZhixin 
     * @Desc    :   退出登录
     */
    public function logoutAdmin()
    {
        $this->setMsg();
        return $this->jsonEncode();
    }
    /**
     * @Time    :   2021/12/29 16:56:47
     * @Author  :   wangZhixin 
     * @Desc    :   用户列表
     */
    public function getUserList()
    {
        $this->setMsg();
        $getLimit = $this->getLimit();
        $limit = $getLimit['limitStart'];
        $pageCount = $getLimit['limitEnd'];

        $list = Db::table('user')->order('user_id', 'desc')->limit($limit, $pageCount)->select();
        if ($list) {
            $count = Db::table('user')->count();
            $this->setData(['list' => $list, 'pageSize' => $pageCount, 'total' => $count]);
        }
        return $this->jsonEncode();
    }
}
