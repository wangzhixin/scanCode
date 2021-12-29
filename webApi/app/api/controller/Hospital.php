<?php

namespace app\api\controller;

use app\api\BaseController;
use think\facade\Db;

class Hospital extends BaseController
{

    /**
     * @Time    :   2021/12/29 17:37:09
     * @Author  :   wangZhixin 
     * @Desc    :   获取医院列表
     */
    public function getHospitalList()
    {
        $this->setMsg();
        $getLimit = $this->getLimit();
        $limit = $getLimit['limitStart'];
        $pageCount = $getLimit['limitEnd'];

        $list = Db::table('hospital')->order('hospital_id', 'desc')->limit($limit, $pageCount)->select();
        if ($list) {
            $count = Db::table('hospital')->count();
            $this->setData(['list' => $list, 'pageSize' => $pageCount, 'total' => $count]);
        }
        return $this->jsonEncode();
    }
    /**
     * @Time    :   2021/12/29 17:38:36
     * @Author  :   wangZhixin 
     * @Desc    :   添加医院
     */
    public function submitHospital()
    {
        $hospital_name = $this->post('hospital_name', '', true);
        $hospital_id = $this->post('hospital_id', '');
        $data['hospital_name'] = $hospital_name;
        if ($hospital_id) {
            $data['hospital_id'] = $hospital_id;
        }
        $check = Db::table('hospital')->save($data);
        if ($check) {
            $this->setMsg();
        }
        return $this->jsonEncode();
    }
    /**
     * @Time    :   2021/12/29 17:44:06
     * @Author  :   wangZhixin 
     * @Desc    :   删除医院
     */

    public function deleteHospital()
    {
        $hospital_id = $this->post('hospital_id', '', true);
        Db::startTrans();
        try {
            Db::table('hospital')->where(['hospital_id' => $hospital_id])->delete();
            Db::table('department')->where(['hospital_id' => $hospital_id])->delete();
            Db::commit();
            $this->setMsg();
        } catch (\Exception $e) {
            Db::rollback();
            getErrorMessage($e);
        }

        return $this->jsonEncode();
    }

    /**
     * @Time    :   2021/12/29 17:40:14
     * @Author  :   wangZhixin 
     * @Desc    :   获取科室列表
     */
    public function getDepartmentList()
    {
        $hospital_id = $this->post('id', '', true);
        $this->setMsg();
        $getLimit = $this->getLimit();
        $limit = $getLimit['limitStart'];
        $pageCount = $getLimit['limitEnd'];

        $list = Db::table('department')->where(['hospital_id'=>$hospital_id])->order('department_id', 'desc')->limit($limit, $pageCount)->select();
        if ($list) {
            $count = Db::table('department')->count();
            $this->setData(['list' => $list, 'pageSize' => $pageCount, 'total' => $count]);
        }
        return $this->jsonEncode();
    }
    /**
     * @Time    :   2021/12/29 17:40:56
     * @Author  :   wangZhixin 
     * @Desc    :   提交科室
     */

    public function submitDepartmentList()
    {
        $department_name = $this->post('department_name', '', true);
        $hospital_id = $this->post('hospital_id', '', true);
        $department_id = $this->post('department_id', '');
        $data['department_name'] = $department_name;
        $data['hospital_id'] = $hospital_id;
        if ($department_id) {
            $data['department_id'] = $department_id;
        }
        $check = Db::table('department')->save($data);
        if ($check) {
            $this->setMsg();
        }
        return $this->jsonEncode();
    }
    /**
     * @Time    :   2021/12/29 17:45:54
     * @Author  :   wangZhixin 
     * @Desc    :   删除科室
     */

    public function deleteDepartmentList()
    {
        $department_id = $this->post('department_id', '', true);
        Db::startTrans();
        try {
            Db::table('department')->where(['department_id' => $department_id])->delete();
            Db::commit();
            $this->setMsg();
        } catch (\Exception $e) {
            Db::rollback();
            getErrorMessage($e);
        }
        return $this->jsonEncode();
    }
}
