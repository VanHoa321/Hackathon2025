<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthorController extends Controller
{
    public function index()
    {
        $items = Author::orderBy('id', 'desc')->get();
        return view('admin.author.index', compact('items'));
    }

    private function validates(Request $request, $id = null)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:authors,email,' . $id . ',id',
            'phone' => 'required|string|max:12|unique:authors,phone,' . $id . ',id',
        ];

        $messages = [
            'name.required' => 'Tên tác giả không được để trống',
            'name.max' => 'Tên tác giả không quá 255 ký tự',
            'email.required' => 'Địa chỉ email không được để trống',
            'email.email' => 'Địa chỉ email không hợp lệ',
            'email.unique' => 'Địa chỉ email này đã được sử dụng',
            'email.max' => 'Địa chỉ email không quá 255 ký tự',
            'phone.required' => 'Số điện thoại không được để trống',
            'phone.max' => 'Số điện thoại không quá 12 ký tự',
            'phone.unique' => 'Số điện thoại này đã được sử dụng',
        ];

        return Validator::make($request->all(), $rules, $messages);
    }

    public function create()
    {
        return view('admin.author.create');
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
            'bio' => $request->bio,
            "avatar" => $request->avatar ? $request->avatar : "/storage/files/1/Avatar/12225935.png",
            'birth_date' => $request->birth_date,
            'approve' => 1,
            'is_active' => 1,
        ];

        Author::create($data);
        $request->session()->put("messenge", ["style" => "success", "msg" => "Thêm mới tác giả thành công"]);
        return redirect()->route("author.index");
    }

    public function edit($id)
    {
        $edit = Author::find($id);
        return view('admin.author.edit', compact('edit'));
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
            'bio' => $request->bio,
            "avatar" => $request->avatar ? $request->avatar : "/storage/files/1/Avatar/12225935.png",
            'birth_date' => $request->birth_date,
            'approve' => 1,
            'is_active' => 1,
        ];

        Author::where('id', $id)->update($data);
        $request->session()->put("messenge", ["style" => "success", "msg" => "Cập nhật thông tin tác giả thành công"]);
        return redirect()->route("author.index");
    }

    public function destroy(string $id)
    {
        $author = Author::find($id);

        if (!$author) {
            return response()->json(['success' => false, 'message' => 'Tác giả không tồn tại'], 404);
        }

        if ($author->documents()->count() > 0) {
            return response()->json(['success' => false, 'message' => 'Không thể xóa tác giả vì đã có sách'], 422);
        }
        
        $author->delete();
        return response()->json(['success' => true, 'message' => 'Xóa tác giả thành công']);
    }
    
    public function changeActive($id){
        $change = Author::find($id);    
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
