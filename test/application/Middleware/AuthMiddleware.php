<?php
namespace App\Middleware;
use App\Libraries\Fasttoken;
class AuthMiddleware {

    /** 
     * Xử lý middleware
     * 
     * @param mixed $request Thông tin request
     * @param callable $next Middleware tiếp theo
     * @return mixed
     */
    public function handle($request, $next) {
        $isLogin = false;
        // Giả sử sử dụng session để kiểm tra người dùng đã đăng nhập
        if (\System\Libraries\Session::has('user_id')) {
            $isLogin = true;
        }
        if (!$isLogin && $access_token = Fasttoken::getToken()){
            $config_security = config('security');
            $me_data = Fasttoken::decodeToken($access_token, $config_security['app_secret']);
            if (isset($me_data['success']) && isset($me_data['data']['user_id']) && isset($me_data['data']['exp']) && $me_data['data']['exp'] > time()){
                \System\Libraries\Session::set('user_id', $me_data['data']['user_id']);
                \System\Libraries\Session::set('role', $me_data['data']['role']);
                // Tái tạo session ID để tránh session fixation
                \System\Libraries\Session::regenerate();
                $isLogin = true;
                unset($me_data);
                unset($config_security);
            }
        }
        if (!$isLogin){
            // Nếu chưa đăng nhập, chuyển hướng đến trang đăng nhập
            redirect(base_url('account/login'));
        }
        // Gọi middleware tiếp theo
        return $next($request);
    }
}