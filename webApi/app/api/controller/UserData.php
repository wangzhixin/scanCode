<?php

namespace app\api\controller;

use app\api\BaseController;
use think\facade\Db;

class UserData extends BaseController
{
  /**
   * @Time    :   2022/01/01 11:04:51
   * @Author  :   wangZhixin 
   * @Desc    :   获取填报信息
   */
  public function getUserDataList()
  {
    $this->setMsg();
    $getLimit = $this->getLimit();
    $limit = $getLimit['limitStart'];
    $pageCount = $getLimit['limitEnd'];
    $list = Db::table('user_data')->order("data_id", "desc")->limit($limit, $pageCount)->select()->toArray();
    if ($list) {
      foreach ($list as &$each) {
        $this->getUserData($each);
      }
      $count = Db::table('user_data')->count();
      $this->setData(['list' => $list, 'pageSize' => $pageCount, 'total' => $count]);
    }
    return $this->jsonEncode();
  }
  /**
   * @Time    :   2022/01/01 14:56:38
   * @Author  :   wangZhixin 
   * @Desc    :   导出信息
   */

  public function exportUserData()
  {
    $ids = $this->post('ids', '', true);
    if ($ids) {
      $tHeader = ['姓名', '状态', '类型', '证件类型', '证件号码', '电话', '省', '市', '区', '地址', '就诊医院', '就诊科室', '失效时间'];
      $problem = Db::table("problem")->where(['type' => 2])->column('problem', 'problem_id');
      foreach ($problem as $eachProblem) {
        $tHeader[] = $eachProblem;
      }
      $list = array();
      $getOrderList = Db::table('user_data')->where('data_id', 'in', $ids)->select()->toArray();
      foreach ($getOrderList as &$each) {
        $this->getUserData($each);
        $eachList = array($each['name'], $each['type'] == '1' ? '正常' : '异常', $each['user_type'], $each['id_type'], $each['id_number'], $each['phone_number'], $each['province_value'], $each['city_value'], $each['district_value'], $each['address'], $each['hospital_value'], $each['department_value'], $each['invalid_time']);
        foreach ($problem as $key => $eachProblem) {
          if (isset($each['problemInputList'][$key])) {
            $eachList[] = $each['problemInputList'][$key]['value'];
          } else {
            $eachList[] = '-';
          }
        }
        $list[] = $eachList;
      }
      $filename = '填报信息' . time();
      $this->setData(['tHeader' => $tHeader, 'list' => $list, 'filename' => $filename]);
    }
    return $this->jsonEncode();
  }
}
