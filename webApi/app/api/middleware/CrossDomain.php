<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2019 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
declare(strict_types=1);

namespace app\api\middleware;

use Closure;
use think\Request;

/**
 * 跨域请求支持
 */
class CrossDomain
{
    /**
     * 允许跨域请求
     * @access public
     * @param Request $request
     * @param Closure $next
     * @param array   $header
     */
    public function handle($request, Closure $next)
    {
        $header = [];
        $origin = $request->server('HTTP_ORIGIN') ? $request->server('HTTP_ORIGIN') : '';
        $allow_origin = config('config.allow_origin') ? config('config.allow_origin') : [];
        if (in_array($origin, $allow_origin)) {
            $header = [
                'Access-Control-Allow-Credentials' => 'true',
                'Access-Control-Max-Age'           => 1800,
                'Access-Control-Allow-Methods'     => 'GET, POST, PATCH, PUT, DELETE, OPTIONS',
                'Access-Control-Allow-Headers'     => config('config.web_header_token_key').', Authorization, Content-Type,',
            ];
            $header['Access-Control-Allow-Origin'] = $origin;
        }
        return $next($request)->header($header);
    }
}
