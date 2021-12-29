<?php

declare(strict_types=1);

namespace app\api\middleware;

use app\api\provider\Jwtcode;
use think\Response;

class Token
{
    public $jwt;

    public function __construct(Jwtcode $jwt)
    {
        $this->jwt = $jwt;
    }
    /**
     * jwt token
     *
     * @param \think\Request $request
     * @param \Closure       $next
     * @return Response
     */
    public function handle($request, \Closure $next)
    {
        $token = "";
        $info = $request->header();
        if (isset($info[config('config.web_header_token_key')])) {
            $token = $info[config('config.web_header_token_key')];
        }
        $data = $this->jwt->getData($token);
        if (!$data) {
            return json(['code' => 50008, 'msg' => '登陆过期，请重新登陆']);
        }
        $request->adminLogin = $data;
        return $next($request);
    }
}
