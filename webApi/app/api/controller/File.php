<?php

namespace app\api\controller;

use app\api\BaseController;
use think\facade\Request;

class File extends BaseController
{
    /**
     * @desc app上传文件
     * @date 2020-08-17
     */
    public function uploadFile()
    {
        $this->setMsg();
        $file = Request::file('file');
        if ($file) {
            $path = \think\facade\Filesystem::disk('public')->putFile('upload', $file);
            if ($path) {
                $setData = array(
                    'url' => config("config.web_url")."/storage/".$path,
                );
                $this->setData($setData);
            } else {
                $this->setMsg('上传失败');
            }
        }
        return $this->jsonEncode();
    }
}
