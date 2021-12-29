<?php

namespace app\api\controller;

use app\api\BaseController;
use think\facade\Db;

class Setting extends BaseController
{
    /**
     * @Time    :   2021/12/29 15:26:29
     * @Author  :   wangZhixin 
     * @Desc    :   设置提交
     */
    public function submitSetting()
    {
        $logo = $this->post('logo', '', true);
        $title = $this->post('title', '', true);
        $title_en = $this->post('title_en', '', true);
        $id = $this->post('id', '');
        $data['logo'] = $logo;
        $data['title'] = $title;
        $data['title_en'] = $title_en;
        if ($id) {
            $data['id'] = $id;
        }
        $check = Db::table('setting')->save($data);
        if ($check) {
            $this->setMsg();
        }
        return $this->jsonEncode();
    }
    /**
     * @Time    :   2021/12/29 15:33:22
     * @Author  :   wangZhixin 
     * @Desc    :   获取设置
     */
    public function getSetting()
    {
        $data['id'] = 0;
        $data['logo'] = '';
        $data['title'] = '';
        $data['title_en'] = '';
        $find = Db::table('setting')->order('id', 'desc')->find();
        if ($find) {
            $data = $find;
        }
        $this->setData($data);
        return $this->jsonEncode();
    }
}
