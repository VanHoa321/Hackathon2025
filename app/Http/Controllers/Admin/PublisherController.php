<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PublisherController extends Controller
{
    public function index()
    {
        $items = Publisher::where("id", ">", 1)->orderBy("id", "desc")->get();
        return view('admin.publisher.index', compact('items'));
    }

    private function validates(Request $request, $id = null)
    {
        $rules = [
            'name' => 'required|string|max:255|unique:publishers,name,' . $id . ',id',
            'email' => 'required|email|max:255|unique:publishers,email,' . $id . ',id',
            'phone' => 'required|string|max:12|unique:publishers,phone,' . $id . ',id',
            'address' => 'nullable|string|max:255',
            'website' => 'nullable|max:255',
        ];

        $messages = [
            'name.required' => 'Tên nhà xuất bản không được để trống',
            'name.unique' => 'Tên nhà xuất bản đã tồn tại',
            'name.max' => 'Tên nhà xuất bản không quá 255 ký tự',
            'email.required' => 'Địa chỉ email không được để trống',
            'email.email' => 'Địa chỉ email không hợp lệ',
            'email.unique' => 'Địa chỉ email này đã được sử dụng',
            'email.max' => 'Địa chỉ email không quá 255 ký tự',
            'phone.required' => 'Số điện thoại không được để trống',
            'phone.max' => 'Số điện thoại không quá 12 ký tự',
            'phone.unique' => 'Số điện thoại này đã được sử dụng',
            'address.max' => 'Địa chỉ không quá 255 ký tự',
            'website.max' => 'Website không quá 255 ký tự',
        ];

        return Validator::make($request->all(), $rules, $messages);
    }

    public function create()
    {
        return view('admin.publisher.create');
    }

    public function store(Request $request)
    {
        $validator = $this->validates($request);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'website' => $request->website,
            'description' => $request->description,
        ];

        Publisher::create($data);
        $request->session()->put("messenge", ["style" => "success", "msg" => "Thêm mới nhà xuất bản thành công"]);
        return redirect()->route("publisher.index");
    }

    public function edit($id)
    {
        $edit = Publisher::findOrFail($id);
        return view('admin.publisher.edit', compact('edit'));
    }

    public function update(Request $request, $id)
    {
        $validator = $this->validates($request, $id);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'website' => $request->website,
            'description' => $request->description,
        ];

        Publisher::where('id', $id)->update($data);
        $request->session()->put("messenge", ["style" => "success", "msg" => "Cập nhật thông tin nhà xuất bản thành công"]);
        return redirect()->route("publisher.index");
    }

    public function destroy(string $id)
    {
        $destroy = Publisher::find($id);
        if ($destroy) {
            $destroy->delete();
            return response()->json(['success' => true, 'message' => 'Xóa nhà xuất bản thành công']);
        } else {
            return response()->json(['danger' => false, 'message' => 'Nhà xuất bản không tồn tại'], 404);
        }
    }
    
    public function changeActive($id){
        $change = Publisher::find($id);    
        if ($change) {
            $change->is_active = !$change->is_active;
            $change->save();
            return response()->json(['success' => true, 'message' => 'Thay đổi trạng thái thành công']);
        }
        else {
            return response()->json(['success' => false, 'message' => 'Thay đổi trạng thái không thành công'], 404);
        }
    }
}
