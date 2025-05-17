<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::first();
       return view("admin.setting.index", compact("setting"));
    }

    public function update(Request $request)
    {

        $validated = $request->validate([
            'max_login' => 'required|integer|min:1|max:100',
            'home_doc_new' => 'required|integer|min:1|max:50',
            'home_doc_download' => 'required|integer|min:1|max:50',
            'home_doc_view' => 'required|integer|min:1|max:50',
            'home_post' => 'required|integer|min:1|max:20',
            'doc_page' => 'required|integer|min:1|max:50',
            'doc_preview' => 'required|integer|min:1|max:50',
        ]);

        $setting = Setting::first();
        $setting->max_login = $validated['max_login'];
        $setting->home_doc_new = $validated['home_doc_new'];
        $setting->home_doc_download = $validated['home_doc_download'];
        $setting->home_doc_view = $validated['home_doc_view'];
        $setting->home_post = $validated['home_post'];
        $setting->doc_page = $validated['doc_page'];
        $setting->doc_preview = $validated['doc_preview'];
        $setting->save();
        $request->session()->put("messenge", ["style"=>"success","msg"=>"Cập nhật cấu hình hệ thống thành công!"]);
        return redirect()->route('setting.index')->with('success', 'Cập nhật cài đặt thành công!');
    }
}
