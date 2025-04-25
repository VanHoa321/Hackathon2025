<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function login(){
        return view("account.login");
    }

    public function postLogin(Request $request) {
        $user = User::where('user_name', $request->user_name)->first();
        if ($user) {
            // if (is_null($user->email_verified_at)) {
            //     $request->session()->put("messenge", ["style" => "danger", "msg" => "Tài khoản chưa được xác minh"]);
            //     return redirect()->route("login");
            // }
            if ($user->status == 0) {
                $request->session()->put("messenge", ["style" => "danger", "msg" => "Tài khoản của bạn đã bị khóa"]);
                return redirect()->route("login");
            }
    
            if (Auth::attempt(["user_name" => $request->user_name, "password" => $request->password, "role_id" => 1])) {
                $request->session()->put("messenge", ["style" => "success", "msg" => "Đăng nhập quyền quản trị thành công"]);
                return redirect()->route("tag.index");
            }
            elseif (Auth::attempt(["user_name" => $request->user_name, "password" => $request->password, "role_id" => 2])) {
                $request->session()->put("messenge", ["style" => "success", "msg" => "Đăng nhập quyền đơn vị sử dụng thành công"]);
                return redirect()->route("homeU.index");
            }
        }
        $request->session()->put("messenge", ["style" => "danger", "msg" => "Thông tin tài khoản không đúng"]);
        return redirect()->route("login");
    }    

    public function logout(){
        Auth::logout();
        return redirect()->route("login");
    }

    public function test(){
        return view("backend.home");
    }
}
