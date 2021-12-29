<?php
declare (strict_types = 1);

namespace app\index\controller;
use think\facade\Db;
use think\facade\View;

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
    public function add(){
        return view();
    }
    public function show(){
        return view();
    }
}
