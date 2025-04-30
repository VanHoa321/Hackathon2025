<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductCategoryController extends Controller
{
    public function index()
    {
        $items = ProductCategory::orderBy("id", "desc")->get();
        return view("admin.product-category.index", compact("items"));
    }

    private function validates(Request $request, $id = null)
    {
        $rules = [
            'name' => 'required|string|max:50|unique:product_categories,name,' . $id . ',id',
        ];

        $messages = [
            'name.required' => 'Tên phân loại sản phẩm không được để trống',
            'name.unique' => 'Tên phân loại sản phẩm đã tồn tại',
            'name.max' => 'Tên phân loại sản phẩm không quá 50 ký tự.',   
        ];

        return Validator::make($request->all(), $rules, $messages);
    }


    public function create()
    {
        return view("admin.product-category.create");
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
        $create = new ProductCategory();
        $create->create($data);
        $request->session()->put("messenge", ["style"=>"success","msg"=>"Thêm mới phân loại sản phẩm thành công"]);
        return redirect()->route("category.index");
    }

    public function edit($id)
    {
        $edit = ProductCategory::where("id", $id)->first();
        return view("admin.product-category.edit", compact("edit"));
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
        $edit = ProductCategory::where('id', $id)->first();
        $edit->update($data);
        $request->session()->put("messenge", ["style"=>"success","msg"=>"Cập nhật phân loại sản phẩm thành công"]);
        return redirect()->route("category.index");
    }

    public function destroy(string $id)
    {
        $destroy = ProductCategory::find($id);
        if ($destroy) {
            $destroy->delete();
            return response()->json(['success' => true, 'message' => 'Xóa phân loại thành công']);
        } else {
            return response()->json(['danger' => false, 'message' => 'Phân loại không tồn tại'], 404);
        }
    }

    public function changeActive($id){
        $change = ProductCategory::find($id);    
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
