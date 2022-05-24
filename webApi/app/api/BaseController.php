<?php

declare(strict_types=1);

namespace app\api;

use think\App;
use think\exception\ValidateException;
use think\Validate;
use \think\facade\Db;
use app\api\provider\Jwtcode;

/**
 * 控制器基础类
 */
abstract class BaseController
{
    /**
     * Request实例
     * @var \think\Request
     */
    protected $request;

    /**
     * 应用实例
     * @var \think\App
     */
    protected $app;
    protected $userId;
    protected $storeId;
    protected $jwt;
    protected $storeUserId;

    /**
     * 是否批量验证
     * @var bool
     */
    protected $batchValidate = false;

    /**
     * 控制器中间件
     * @var array
     */
    protected $middleware = [];

    protected $result = array('code' => 0, 'msg' => '操作失败', 'data' => []);

    /**
     * 构造方法
     * @access public
     * @param  App  $app  应用对象
     */
    public function __construct(App $app, Jwtcode $jwt)
    {
        $this->app     = $app;
        $this->request = $this->app->request;
        $this->jwt = $jwt;
        $data = $this->app->request->adminLogin;
        if ($data) {
            $this->userId = $data->userId;
        } else {
            return json(['code' => 50014, 'msg' => '登陆过期']);
        }
        // 控制器初始化
        $this->initialize();
        if (config('config.show_api_time') == true) {
            $this->result['api_time']['api_time_start'] = microtime_float();
        }
    }

    // 初始化
    protected function initialize()
    {
    }

    /**
     * 验证数据
     * @access protected
     * @param  array        $data     数据
     * @param  string|array $validate 验证器名或者验证规则数组
     * @param  array        $message  提示信息
     * @param  bool         $batch    是否批量验证
     * @return array|string|true
     * @throws ValidateException
     */
    protected function validate(array $data, $validate, array $message = [], bool $batch = false)
    {
        if (is_array($validate)) {
            $v = new Validate();
            $v->rule($validate);
        } else {
            if (strpos($validate, '.')) {
                // 支持场景
                [$validate, $scene] = explode('.', $validate);
            }
            $class = false !== strpos($validate, '\\') ? $validate : $this->app->parseClass('validate', $validate);
            $v     = new $class();
            if (!empty($scene)) {
                $v->scene($scene);
            }
        }

        $v->message($message);

        // 是否批量验证
        if ($batch || $this->batchValidate) {
            $v->batch(true);
        }

        return $v->failException(true)->check($data);
    }
    protected function setMsg($msg = 'ok')
    {
        $this->result['msg'] = $msg;
        if ($msg == 'ok' || $msg == 'error') {
            $this->setCode(1);
        }
    }
    protected function setCode($code)
    {
        $this->result['code'] = $code;
    }
    protected function setData($data)
    {
        $this->setMsg();
        $this->result['data'] = $data;
    }
    protected function jsonEncode()
    {
        if (config('config.show_api_time') == true) {
            if (isset($this->result['api_time']['api_time_start'])) {
                $this->result['api_time']['api_time_end'] = microtime_float();
                $this->result['api_time']['api_time'] = $this->result['api_time']['api_time_end'] - $this->result['api_time']['api_time_start'];
                //记录日志
                setLog(json_encode($this->result['api_time']) . '------' . json_encode($this->request->post()));
            }
        }
        return json($this->result);
    }
    /**
     * @desc 后台账户铭文返回密码
     * @date 2020-07-17
     */
    protected function getPasswordMd5Store(string $txt)
    {
        return md5(md5($txt . config('config.MD5KEY')));
    }
    /**
     * @desc 后台账户铭文返回密码
     * @date 2020-07-17
     */
    protected function getPasswordMd5Admin(string $txt)
    {
        return getPasswordMd5Admin($txt);
    }
    /**
     * @Time    :   2021/10/21 16:54:42
     * @Author  :   wangZhixin 
     * @Desc    :   post接收数据
     */
    protected function post($filed, $init = '', $required = false)
    {
        $return = $this->request->post($filed, $init);
        if ($required && $return == "") {
            echo json_encode($this->result);
            // return $this->jsonEncode();
            exit;
        }
        return $return;
    }
    /**
     * @Time    :   2021/11/30 14:13:50
     * @Author  :   wangZhixin 
     * @Desc    :   分页limit
     */
    protected function getLimit($pageCount = 0)
    {
        $return = array('limitStart' => 0, 'limitEnd' => 0);
        $page = $this->request->post('page', 1);
        if ($page) {
            if ($pageCount == 0) {
                $pageCount = config('config.page');
            }
            $return['limitStart'] = ($page - 1) * $pageCount;
            $return['limitEnd'] = $pageCount;
        }
        return $return;
    }
    /**
     * @Time    :   2021/11/30 14:38:57
     * @Author  :   wangZhixin 
     * @Desc    :   获取排序ID
     */
    protected function getDropOrderId($key, $pageCount = 0)
    {
        $page = $this->request->post('page', 1);
        if ($pageCount == 0) {
            $pageCount = config('config.page');
        }
        return ($key + 1) + $pageCount * ($page - 1);
    }
    /**
     * @Time    :   2021/12/01 15:07:03
     * @Author  :   wangZhixin 
     * @Desc    :   设备判断 1：安卓；2：ios
     */
    protected function isIos($deviceModel)
    {
        $os     = 1;
        $iosCheck     = strtolower(substr($deviceModel, 0, 6));
        if ($iosCheck == 'iphone') {
            $os = 2;
        }
        return $os;
    }
    protected function getDeliverDetail($order_id)
    {
        $orderDeliver = Db::table('order_deliver')->field('order_deliver_id,order_deliver_name')->where(['order_id' => $order_id, 'type' => 0, 'is_deleted' => 0])->find();
        return $orderDeliver;
    }
    protected function getUserData(&$each)
    {
        $problemList = array();
        foreach (json_decode($each['problemList'], true) as $eachProblem) {
            $id = 0;
            $explodeV = explode('v_', $eachProblem['id']);
            if (isset($explodeV[1])) {
                $id = $explodeV[1];
            }
            $explodeInput = explode('input_', $eachProblem['id']);
            if (isset($explodeInput[1])) {
                $id = $explodeInput[1];
            }
            if ($id) {
                $problem = Db::table('problem')->where(['problem_id' => $id])->value('problem');
                $problemList[] = [
                    'problem' => $problem,
                    'value' => $eachProblem['value']
                ];
            }
        }
        $each['problemList'] = $problemList;

        $user_type = '本人';
        if ($each['user_type'] == 1) {
            $user_type = '同行人员1';
        }
        if ($each['user_type'] == 2) {
            $user_type = '同行人员2';
        }
        $each['user_type'] = $user_type;

        $each['invalid_time'] = date("Y-m-d H:i:s", $each['invalid_time']);

        switch ($each['id_type']) {
            case 1:
                $id_type = '身份证';
                break;
            case 2:
                $id_type = '港澳台通行证';
                break;
            case 3:
                $id_type = '护照';
                break;
            default:
                $id_type = "";
                break;
        }
        $each['id_type'] = $id_type;
    }
}
