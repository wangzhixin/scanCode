<?php

namespace app\api\controller;

use app\api\BaseController;
use think\facade\Db;

class AdminUser extends BaseController
{
  /**
   * @Time    :   2022/01/01 15:34:42
   * @Author  :   wangZhixin 
   * @Desc    :   获取管理员列表
   */
  public function getAdminUserList()
  {
    $this->setMsg();
    $getLimit = $this->getLimit();
    $limit = $getLimit['limitStart'];
    $pageCount = $getLimit['limitEnd'];
    $where['is_deleted'] = 0;
    $where['admin_type'] = 2;
    $get = Db::table("admin_users")->where(['admin_type' => 1])->select()->toArray();
    $list = Db::table("admin_users")->where($where)->order('admin_id', 'desc')->limit($limit, $pageCount)->select()->toArray();
    $list = array_merge($get, $list);
    if ($list) {
      $count = Db::table('admin_users')->where($where)->count();
      $this->setData(['list' => $list, 'pageSize' => $pageCount, 'total' => $count]);
    }
    return $this->jsonEncode();
  }
  /**
   * @Time    :   2022/01/01 15:38:45
   * @Author  :   wangZhixin 
   * @Desc    :   删除账户
   */
  public function deleteAdminUser()
  {
    $this->setMsg();
    $admin_id = $this->post("admin_id", '', true);
    Db::table("admin_users")->where(['admin_id' => $admin_id])->update(['is_deleted' => 1]);
    return $this->jsonEncode();
  }
  /**
   * @Time    :   2022/01/01 15:40:03
   * @Author  :   wangZhixin 
   * @Desc    :   提交账户
   */
  public function submitAdminUser()
  {
    $this->setMsg();
    $admin_id = $this->post("admin_id", '');
    $admin_name = $this->post('admin_name', "", true);
    $admin_password = $this->post('admin_password', "", true);
    $data['admin_name'] = $admin_name;
    $data['admin_type'] = 2;
    $data['admin_password'] = getPasswordMd5Admin($admin_password);
    if ($admin_id) {
      $data['admin_id'] = $admin_id;
    }
    if ($this->userId == $admin_id) {
      $data['admin_type'] = 1;
    }
    Db::table("admin_users")->save($data);
    return $this->jsonEncode();
  }
}
