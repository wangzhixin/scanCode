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
        View::assign('apiUrl', getConfig("api.config.web_url")."/index.php/index/");
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
}
