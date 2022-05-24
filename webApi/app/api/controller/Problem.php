<?php

namespace app\api\controller;

use app\api\BaseController;
use think\facade\Db;

class Problem extends BaseController
{
    /**
     * @Time    :   2021/12/29 15:59:15
     * @Author  :   wangZhixin 
     * @Desc    :   筛查问题列表
     */
    public function problemList()
    {
        $list = Db::table('problem')->where(['is_deleted' => 0])->order('problem_id', 'desc')->select();
        $this->setData(['list' => $list]);
        return $this->jsonEncode();
    }
    /**
     * @Time    :   2021/12/29 16:13:24
     * @Author  :   wangZhixin 
     * @Desc    :   添加问题
     */
    public function submitProblem()
    {
        $type = $this->post('type', '', true);
        $problem = $this->post('problem', '', true);
        $problem_en = $this->post('problem_en', '', true);
        $problem_id = $this->post('problem_id', '');
        $img = $this->post('img', '');
        $data['problem'] = $problem;
        $data['problem_en'] = $problem_en;
        $data['img'] = $img;
        $data['type'] = $type;
        if ($problem_id) {
            $data['problem_id'] = $problem_id;
        }
        $check = Db::table('problem')->save($data);
        if ($check) {
            $this->setMsg();
        }
        return $this->jsonEncode();
    }
    /**
     * @Time    :   2021/12/29 16:23:00
     * @Author  :   wangZhixin 
     * @Desc    :   删除问题
     */
    public function deleteProblem()
    {
        $problem_id = $this->post('problem_id', '', true);
        $check = Db::table('problem')->where(['problem_id' => $problem_id])->update(['is_deleted' => 1]);
        if ($check) {
            $this->setMsg();
        }
        return $this->jsonEncode();
    }
}
