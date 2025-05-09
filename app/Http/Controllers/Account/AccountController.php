<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
                $user->update(['last_login' => now()]);
                $request->session()->put("messenge", ["style" => "success", "msg" => "Đăng nhập quyền quản trị thành công"]);
                return redirect()->route("tag.index");
            }
            elseif (Auth::attempt(["user_name" => $request->user_name, "password" => $request->password, "role_id" => 2])) {
                $user->update(['last_login' => now()]);
                $request->session()->put("messenge", ["style" => "success", "msg" => "Đăng nhập quyền người dùng thành công"]);
                return redirect()->route("homeU.index");
            }
        }
        $request->session()->put("messenge", ["style" => "danger", "msg" => "Thông tin tài khoản không đúng"]);
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
