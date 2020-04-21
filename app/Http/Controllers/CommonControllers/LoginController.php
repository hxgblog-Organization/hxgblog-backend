<?php
namespace App\Http\Controllers\CommonControllers;

use App\Http\Controllers\Controller;
use App\Model\Users;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;


    /**
     * 登录使用的账号字段名
     * @return string
     */
    public function username()
    {
        return 'phone';
    }


    /**
     * 认证服务方（登录时需要使用SessionGuard, 访问api时使用TokenGuard）
     * @return mixed
     */
    public function guard()
    {
        return Auth::guard('web');              //session
    }


    /**
     * 前台用户登录
     * @param Request $request
     * @return JsonResponse
     */
    public function frontLogin(Request $request)
    {
        if (! captcha_check($request->input('captcha_code'))) {
            return responseToJson(1, '验证码不正确');
        }
        if ($this->attemptLogin($request)) {
            $user = updateLoginAuth();
            return responseToJson(0, '登录成功',$user);
        }
        return responseToJson(2, '账号或密码不正确');
    }


    /**
     * 后台用户登录
     * @param Request $request
     * @return JsonResponse
     */
    public function backLogin(Request $request)
    {
        if (! captcha_check($request->input('captcha_code'))) {
            return responseToJson(1, '验证码不正确');
        }
        if ($this->attemptLogin($request)) {
            $user = updateLoginAuth(true);
            return responseToJson(0, '登录成功', $user);
        }
        return responseToJson(2, '账号或密码不正确');
    }


    /**
     * 前台短信登录
     * @param Request $request
     * @return JsonResponse
     */
    public function frontSmsLogin(Request $request)
    {
        $sms_code = $request->input('sms_code');
        $phone = $request->input('phone');
        $validateSms = validateSmsLogin($sms_code, $phone);
        if ($validateSms['code'] == 1) {
            return responseToJson(1, $validateSms['msg']);
        }
        $user = updateLoginAuth(false, Users::LOGIN_WAY_SMS, $phone);
        return responseToJson(0, '登录成功', $user);
    }


    /**
     * 后台短信登陆
     * @param Request $request
     * @return JsonResponse
     */
    public function backSmsLogin(Request $request)
    {
        $sms_code = $request->input('sms_code');
        $phone = $request->input('phone');
        $validateSms = validateSmsLogin($sms_code, $phone);
        if ($validateSms['code'] == 1) {
            return responseToJson(1, $validateSms['msg']);
        }
        $user = updateLoginAuth(true, Users::LOGIN_WAY_SMS, $phone);
        return responseToJson(0, '登录成功', $user);
    }


    /**
     * 前台用户退出
     * @return JsonResponse
     */
    public function frontLogout()
    {
        $user = Auth::guard('api')->user();     //用户退出，token清空
        if ($user) {
            $user->api_token = null;
            $user->save();
        }
        session()->forget('user');
        return responseToJson(0, '退出成功', session('user'));
    }


    /**
     * 后台用户退出
     * @return JsonResponse
     */
    public function backLogout()
    {
        $user = Auth::guard('api')->user();     //用户退出，token清空
        if ($user) {
            $user->api_token = null;
            $user->save();
        }
        session()->forget('admin');
        return responseToJson(0, '退出成功');
    }


    /**登录成功之后调用
     * @param $information
     * @param bool $is_admin
     */
    public function loginSuccess($information, $is_admin = false)
    {
        ($is_admin) ? session(['admin' => $information]) : session(['user' => $information]);
    }


    /**
     * 判断 '前台用户 / 后台管理员' 是否处于登录状态
     * @param Request $request
     * @return JsonResponse
     */
    public function checkLogin(Request $request)
    {
        if (($request->input('status') == 1) && empty(session('user'))) {
            return responseToJson(2, '你未登录');
        } else {
            if (empty(session('admin'))) {
                return responseToJson(2, '你未登录');
            }
        }
        return responseToJson(0, '已登录');
    }


    /**
     * 判断后台管理员与前台用户是否同时在线
     * @return JsonResponse
     */
    public function checkUserOrAdminLogin(){
        if (empty(session('user')) && empty(session('admin'))) {
            return responseToJson(2, '未登录');
        }
        return responseToJson(1, '已登录', session('admin'));
    }


}
