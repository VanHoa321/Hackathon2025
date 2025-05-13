<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DocumentCategoryController extends Controller
{
    public function index()
    {
        $items = DocumentCategory::orderBy("id", "desc")->get();
        return view("admin.document-category.index", compact("items"));
    }

    private function validates(Request $request, $id = null)
    {
        $rules = [
            'name' => 'required|string|max:50|unique:document_categories,name,' . $id . ',id',
        ];

        $messages = [
            'name.required' => 'Tên phân loại tài liệu không được để trống',
            'name.unique' => 'Tên phân loại tài liệu đã tồn tại',
            'name.max' => 'Tên phân loại tài liệu không quá 50 ký tự.',   
        ];

        return Validator::make($request->all(), $rules, $messages);
    }

    public function create()
    {
        return view("admin.document-category.create");
    }

    public function store(Request $request){
        $validator = $this->validates($request);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $data = [
            'name' => $request->name,
            'description'=> $request->description,
        ];
        $create = new DocumentCategory();
        $create->create($data);
        $request->session()->put("messenge", ["style"=>"success","msg"=>"Thêm mới phân loại tài liệu thành công"]);
        return redirect()->route("document-category.index");
    }

    public function edit($id)
    {
        $edit = DocumentCategory::where("id", $id)->first();
        return view("admin.document-category.edit", compact("edit"));
    }

    public function update(Request $request, $id)
    {
        $validator = $this->validates($request, $id);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $data = [
            'name' => $request->name,
            'description'=> $request->description,
        ];
        $edit = DocumentCategory::where('id', $id)->first();
        $edit->update($data);
        $request->session()->put("messenge", ["style"=>"success","msg"=>"Cập nhật phân loại tài liệu thành công"]);
        return redirect()->route("document-category.index");
    }

    public function destroy(string $id)
    {
        $check = Document::where('category_id', $id)->exists();

        if ($check) {
            return response()->json(['success' => false,'message' => 'Không thể xóa vì có tài liệu thuộc phân loại này']);
        }

        $destroy = DocumentCategory::find($id);
        if ($destroy) {
            $destroy->delete();
            return response()->json(['success' => true, 'message' => 'Xóa phân loại thành công']);
        } else {
            return response()->json(['danger' => false, 'message' => 'Phân loại không tồn tại'], 404);
        }
    }

    public function changeActive($id){
        $change = DocumentCategory::find($id);    
        if ($change) {
            $change->is_active = !$change->is_active;
            $change->save();

            Document::where('category_id', $change->id)->update([
                'status' => $change->is_active
            ]);

            return response()->json(['success' => true, 'message' => 'Thay đổi trạng thái thành công']);
        }
        else {
            return response()->json(['success' => false, 'message' => 'Thay đổi trạng thái không thành công'], 404);
        }
    }
}
