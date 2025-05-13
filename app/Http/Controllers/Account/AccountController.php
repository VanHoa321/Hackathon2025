<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function login(){
        return view("account.login");
    }

    public function postLogin(Request $request) {
        $username = $request->user_name;
        $cacheKey = "login_attempts:{$username}";
        $lockoutKey = "lockout:{$username}";

        if (Cache::has($lockoutKey)) {
            $request->session()->put("messenge", ["style" => "danger", "msg" => "Tài khoản bị khóa tạm thời do nhập sai mật khẩu quá nhiều. Vui lòng thử lại sau 5 phút."]);
            return response()->redirectToRoute("login")->setStatusCode(Response::HTTP_FORBIDDEN);
        }

        $user = User::where('user_name', $request->user_name)->first();
        if ($user) {
            if ($user->status == 0) {
                $request->session()->put("messenge", ["style" => "danger", "msg" => "Tài khoản của bạn đã bị khóa"]);
                return redirect()->route("login");
            }

            $attempts = Cache::get($cacheKey, 0);
            if ($attempts >= 3) {
                
                Cache::put($lockoutKey, true, now()->addMinutes(5));
                Cache::forget($cacheKey);
                $request->session()->put("messenge", ["style" => "danger", "msg" => "Tài khoản bị khóa tạm thời do nhập sai mật khẩu quá nhiều. Vui lòng thử lại sau 5 phút."]);
                return response()->redirectToRoute("login")->setStatusCode(Response::HTTP_FORBIDDEN);
            }

            if (Auth::attempt(["user_name" => $request->user_name, "password" => $request->password, "role_id" => 1])) {

                $user->update(['last_login' => now()]);
                Cache::forget($cacheKey);
                $request->session()->put("messenge", ["style" => "success", "msg" => "Đăng nhập quyền quản trị thành công"]);
                return redirect()->route("tag.index");
            } 
            elseif (Auth::attempt(["user_name" => $request->user_name, "password" => $request->password, "role_id" => 2])) {

                $user->update(['last_login' => now()]);
                Cache::forget($cacheKey);
                $request->session()->put("messenge", ["style" => "success", "msg" => "Đăng nhập quyền người dùng thành công"]);
                return redirect()->route("homeU.index");
            }

            Cache::increment($cacheKey);
            Cache::put($cacheKey, Cache::get($cacheKey), now()->addMinutes(5));
            $request->session()->put("messenge", ["style" => "danger", "msg" => "Thông tin đăng nhập không chính xác"]);
            return redirect()->route("login");
        }

        $request->session()->put("messenge", ["style" => "danger", "msg" => "Thông tin đăng nhập không chính xác"]);
        return redirect()->route("login");
    }    

    public function logout(){
        Auth::logout();
        return redirect()->route("frontend.home.index");
    }

    public function profile(){
        $user = Auth::user();
        return view('account.profile', compact('user'));
    }

    public function editProfile(){
        $user = Auth::user();
        return view('account.edit_profile', compact("user"));
    }

    public function updateProfile(Request $request){
        $oldUser = Auth::user();
        $id = $oldUser->id;
        $update = User::find($id);
        $data = [
            'name' => $request->name,
            'email'=> $request->email,
            'phone'=> $request->phone,
            'address'=> $request->address,
            'avatar'=> $request->avatar,
            'description'=> $request->description,
        ];
        $update->update($data);
        $request->session()->put("messenge", ["style" => "success", "msg" => "Cập nhật hồ sơ thành công"]);
        return redirect()->route('profile');
    }

    public function editPassword(){
        $user = Auth::user();
        return view('account.edit_password', compact("user"));
    }

    public function updatePassword(Request $request){
        $user = Auth::user();
        if (!Hash::check($request->old_password, $user->password)) {
            $request->session()->put("messenge", ["style" => "danger", "msg" => "Mật khẩu cũ không đúng"]);
            return redirect()->back();
        }
        $id = $user->id;
        $update = User::find($id);
        $update->password = Hash::make($request->new_password);
        $update->save();
        $request->session()->put("messenge", ["style" => "success", "msg" => "Cập nhật mật khẩu thành công"]);
        return redirect()->route('profile');
    }
}
