<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DocumentController extends Controller
{
    public function index()
    {
        $items = Document::with('category', 'publisher')->orderBy('id', 'desc')->get();
        return view('admin.document.index', compact('items'));
    }

    private function validates(Request $request, $id = null)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:document_categories,id',
            'publisher_id' => 'required|exists:publishers,id',
            'file_path' => 'required|string|max:255',
            'is_free' => 'required|in:0,1',
            'price' => 'nullable|numeric|min:0',
            'description' => 'required|string',
            'publication_year' => 'nullable|digits:4|integer|min:1900|max:' . date('Y'),
        ];

        $messages = [
            'title.required' => 'Tiêu đề không được để trống',
            'title.max' => 'Tiêu đề tối đa 255 ký tự',
            'category_id.required' => 'Vui lòng chọn danh mục',
            'category_id.exists' => 'Danh mục không hợp lệ',
            'publisher_id.required' => 'Vui lòng chọn nhà xuất bản',
            'publisher_id.exists' => 'Nhà xuất bản không hợp lệ',
            'file_path.required' => 'Vui lòng chọn file',
            'is_free.required' => 'Vui lòng chọn hình thức tài liệu',
            'is_free.in' => 'Giá trị hình thức không hợp lệ',
            'price.numeric' => 'Phí tải về phải là số',
            'price.min' => 'Phí tải về không được âm',
            'description.required' => 'Mô tả không được để trống.',
            'publication_year.digits' => 'Năm xuất bản phải là 4 chữ số',
            'publication_year.integer' => 'Năm xuất bản phải là số nguyên',
            'publication_year.min' => 'Năm xuất bản không hợp lệ',
            'publication_year.max' => 'Năm xuất bản không được lớn hơn hiện tại',
        ];

        return Validator::make($request->all(), $rules, $messages);
    }

    public function create()
    {
        $publishers = Publisher::where("is_active", 1)->get();
        $categories = DocumentCategory::where("is_active", 1)->orderBy("id", "asc")->get();
        $authors = Author::where("is_active", 1)->orderBy("id", "asc")->get();
        return view('admin.document.create', compact('publishers', 'categories', 'authors'));
    }

    public function store(Request $request)
    {
        $validator = $this->validates($request);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = [
            'title' => $request->title,
            'category_id' => $request->category_id,
            'publisher_id' => $request->publisher_id,
            'cover_image' => $request->cover_image,
            'file_path' => $request->file_path,
            'file_format' => pathinfo($request->file_path, PATHINFO_EXTENSION),
            'is_free' => $request->is_free,
            "price" => $request->price,
            'description' => $request->description,
            'publication_year' => $request->publication_year,
            'uploaded_by' => Auth::user()->id,
            'status' => 1
        ];

        $document = Document::create($data);
        if ($request->has('authors')) {
            $document->authors()->sync($request->authors);
        }
        $request->session()->put("messenge", ["style" => "success", "msg" => "Thêm mới tài liệu thành công"]);
        return redirect()->route("document.index");
    }

    public function edit($id)
    {
        $edit = Document::with('authors')->findOrFail($id);
        $publishers = Publisher::where("is_active", 1)->get();
        $selectedAuthors = $edit->authors->pluck('id')->toArray();
        $categories = DocumentCategory::where("is_active", 1)->orderBy("id", "asc")->get();
        $authors = Author::all();
        return view('admin.document.edit', compact('edit','publishers', 'categories', 'authors', 'selectedAuthors'));
    }

    public function update(Request $request, $id)
    {
        $validator = $this->validates($request);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = [
            'title' => $request->title,
            'category_id' => $request->category_id,
            'publisher_id' => $request->publisher_id,
            'file_path' => $request->file_path,
            'file_format' => pathinfo($request->file_path, PATHINFO_EXTENSION),
            'is_free' => $request->is_free,
            'price' => $request->price,
            'description' => $request->description,
            'publication_year' => $request->publication_year,
            'uploaded_by' => Auth::user()->id,
            'status' => 1
        ];

        $document = Document::findOrFail($id);
        $document->update($data);
        if ($request->has('authors')) {
            $document->authors()->sync($request->authors);
        }

        $request->session()->put("messenge", ["style" => "success", "msg" => "Cập nhật thông tin tài liệu thành công"]);
        return redirect()->route("document.index");
    }

    public function destroy(string $id)
    {
        $destroy = Document::find($id);
        if ($destroy) {
            $destroy->delete();
            return response()->json(['success' => true, 'message' => 'Xóa tài liệu thành công']);
        } else {
            return response()->json(['danger' => false, 'message' => 'Tài liệu không tồn tại'], 404);
        }
    }
    
    public function changeActive($id){
        $change = Document::find($id);    
        $category = DocumentCategory::find($change->category_id);
        if ($change) {
            $change->status = !$change->status;
            $change->save();
            return response()->json(['success' => true, 'message' => 'Thay đổi trạng thái thành công']);
        }
        else {
            return response()->json(['success' => false, 'message' => 'Thay đổi trạng thái không thành công'], 404);
        }
    }
}
