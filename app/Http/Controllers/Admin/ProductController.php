<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $items = Product::with('category')->orderBy('created_at', 'desc')->get();
        return view('admin.product.index', compact('items'));
    }

    public function create()
    {
        $categories = ProductCategory::where('is_active', 1)->get();
        return view('admin.product.create', compact('categories'));
    }

    public function store(Request $request){
        $data = [
            'name' => $request->name,
            'image' => $request->image,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'sale_price' => $request->sale_price,
            'abstract'=> $request->abstract,
            'content' => $request->content
        ];
        $create = new Product();
        $create->create($data);
        $request->session()->put("messenge", ["style"=>"success","msg"=>"Thêm mới sản phẩm thành công"]);
        return redirect()->route("product.index");
    }

    public function edit($id)
    {
        $edit = Product::find($id);
        $categories = ProductCategory::where('is_active', 1)->get();
        return view('admin.product.edit', compact('edit', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $data = [
            'name' => $request->name,
            'image' => $request->image,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'sale_price' => $request->sale_price,
            'abstract'=> $request->abstract,
            'content' => $request->content
        ];
        Product::where('id', $id)->update($data);
        $request->session()->put("messenge", ["style"=>"success","msg"=>"Cập nhật sản phẩm thành công"]);
        return redirect()->route("product.index");
    }

    public function destroy(string $id)
    {
        $destroy = Product::find($id);
        if ($destroy) {
            $destroy->delete();
            return response()->json(['success' => true, 'message' => 'Xóa sản phẩm thành công']);
        } else {
            return response()->json(['danger' => false, 'message' => 'Sản phẩm không tồn tại'], 404);
        }
    }

    public function changeActive($id){
        $change = Product::find($id);    
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
