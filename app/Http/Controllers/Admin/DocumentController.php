<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\Publisher;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class DocumentController extends Controller
{
    public function index()
    {
        $items = Document::with('category', 'publisher')->where("approve", 1)->orderBy('id', 'desc')->get();
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

        $full_path = $request->file_path;
        $uid = Auth::user()->id;

        $relative_path = preg_replace('#^https?://[^/]+/storage/#', '', $full_path);
        $file_extension = strtolower(pathinfo($relative_path, PATHINFO_EXTENSION));
        $file_path_pdf = null;

        if ($file_extension !== 'pdf') {
            // Đường dẫn cục bộ tới tệp
            $input_path = storage_path('app/public/' . $relative_path);
            $output_dir = storage_path('app/public/files/pdf/' . $uid . '/');
            $output_filename = pathinfo($relative_path, PATHINFO_FILENAME) . '.pdf';
            $output_path = $output_dir . DIRECTORY_SEPARATOR . $output_filename;

            // Thay thế / thành \ cho Windows
            $input_path = str_replace('/', '\\', $input_path);
            $output_dir = str_replace('/', '\\', $output_dir);
            $output_path = str_replace('/', '\\', $output_path);

            // Đảm bảo thư mục đầu ra tồn tại
            if (!file_exists($output_dir)) {
                mkdir($output_dir, 0775, true);
            }

            // Kiểm tra xem tệp đầu vào tồn tại
            if (!file_exists($input_path)) {
                return redirect()->back()->with('messenge', ['style' => 'danger', 'msg' => 'Tệp đầu vào không tồn tại: ' . $input_path]);
            }

            // Đường dẫn tới soffice.exe
            $soffice_path = 'C:\\Program Files\\LibreOffice\\program\\soffice.exe';
            // Xây dựng lệnh
            $command = [
                escapeshellarg($soffice_path),
                '--headless',
                '--convert-to',
                'pdf',
                escapeshellarg($input_path),
                '--outdir',
                escapeshellarg($output_dir)
            ];

            // Ghi log lệnh
            $command_str = implode(' ', $command);

            // Thử chạy lệnh bằng shell_exec để lấy đầu ra chi tiết
            $output = shell_exec($command_str . ' 2>&1');

            // Kiểm tra xem tệp PDF đã được tạo
            if (file_exists($output_path)) {
                $file_path_pdf = 'files/pdf/' . $uid . '/' . $output_filename;
            } else {
                return redirect()->back()->with('messenge', ['style' => 'danger', 'msg' => 'Không thể tạo tệp PDF. Kiểm tra log để biết chi tiết.']);
            }
        }

        $pdf_for_flask = $file_path_pdf ?? $relative_path;
        $pdf_full_path = storage_path('app/public/' . $pdf_for_flask);

        $response = Http::post('http://127.0.0.1:5007/upload-document', [
            'pdf_path' => $pdf_full_path,
        ]);

        Log::info('Flask API Response: ' . json_encode($response->json(), JSON_UNESCAPED_UNICODE));

        if (!$response->ok()) {
            $request->session()->put("messenge", ["style" => "danger", "msg" => "Kết nối đến Flask thất bại"]);
        }

        $resData = $response->json();

        if (isset($resData['message']) && $resData['message'] === 'Tài liệu này đã tồn tại trong hệ thống') {
            $similarDocs = $resData['similar_docs'] ?? [];

            $maxScore = collect($similarDocs)->max('similarity_score') ?? 0;
            $score = round($maxScore * 100, 2);

            return redirect()->back()->with('messenge', [
                'style' => 'danger',
                'msg' => "Tài liệu đã tồn tại. Độ tương đồng cao nhất: {$score}%"
            ]);
        }

        $vector_path = $resData['vector_path'];
        if (!$vector_path) {
            return redirect()->back()->with('messenge', ['style' => 'danger', 'msg' => 'Không thể tạo vector cho tài liệu']);
        }

        $data = [
            'title' => $request->title,
            'category_id' => $request->category_id,
            'publisher_id' => $request->publisher_id,
            'cover_image' => $request->cover_image ? $request->cover_image : "/storage/files/1/Avatar/no-image.jpg",
            'file_path' => $relative_path,
            'file_path_pdf' => $file_path_pdf,
            'vector_path' => $vector_path,
            'file_format' => $file_extension,
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

        Transaction::create([
            'user_id' => Auth::user()->id,
            'type' => 3,
            'document_id' => $document->id,
            'note' => 'Tải lên tài liệu: ' . $document->title,
        ]);

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

        $document = Document::findOrFail($id);

        $old_file_path = $document->file_path;
        $old_file_pdf = $document->file_path_pdf;
        $old_vector_path = $document->vector_path;

        $new_file_path = $request->file_path;
        $file_path_pdf = $old_file_pdf;
        $vector_path = $old_vector_path;

        if ($new_file_path !== $old_file_path) {
            // Xóa file cũ
            if ($old_file_pdf && file_exists(storage_path('app/public/' . $old_file_pdf))) {
                unlink(storage_path('app/public/' . $old_file_pdf));
            }
            $full_vector_path = storage_path('app/public/' . $old_vector_path);

            if (File::isDirectory($full_vector_path)) {
                File::deleteDirectory($full_vector_path);
            }

            $uid = Auth::user()->id;
            $relative_path = preg_replace('#^https?://[^/]+/storage/#', '', $new_file_path);
            $file_extension = strtolower(pathinfo($relative_path, PATHINFO_EXTENSION));

            if ($file_extension !== 'pdf') {
                $input_path = storage_path('app/public/' . $relative_path);
                $output_dir = storage_path('app/public/files/pdf/' . $uid . '/');
                $output_filename = pathinfo($relative_path, PATHINFO_FILENAME) . '.pdf';
                $output_path = $output_dir . DIRECTORY_SEPARATOR . $output_filename;

                if (!file_exists($output_dir)) {
                    mkdir($output_dir, 0775, true);
                }

                if (!file_exists($input_path)) {
                    return redirect()->back()->with('messenge', ['style' => 'danger', 'msg' => 'Tệp đầu vào không tồn tại: ' . $input_path]);
                }

                $soffice_path = 'C:\\Program Files\\LibreOffice\\program\\soffice.exe';
                $command = [
                    escapeshellarg($soffice_path),
                    '--headless',
                    '--convert-to',
                    'pdf',
                    escapeshellarg($input_path),
                    '--outdir',
                    escapeshellarg($output_dir)
                ];
                $command_str = implode(' ', $command);
                $output = shell_exec($command_str . ' 2>&1');

                if (file_exists($output_path)) {
                    $file_path_pdf = 'files/pdf/' . $uid . '/' . $output_filename;
                } else {
                    return redirect()->back()->with('messenge', ['style' => 'danger', 'msg' => 'Không thể tạo tệp PDF. Kiểm tra log để biết chi tiết.']);
                }
            }

            $pdf_for_flask = $file_path_pdf ?? $relative_path;
            $pdf_full_path = storage_path('app/public/' . $pdf_for_flask);

            $response = Http::post('http://127.0.0.1:5007/upload-document', [
                'pdf_path' => $pdf_full_path,
            ]);

            Log::info('Flask API Response: ' . json_encode($response->json(), JSON_UNESCAPED_UNICODE));

            if (!$response->ok()) {
                return redirect()->back()->with('messenge', ['style' => 'danger', 'msg' => 'Gửi file đến Flask thất bại']);
            }

            $resData = $response->json();

            if (isset($resData['message']) && $resData['message'] === 'Tài liệu này đã tồn tại trong hệ thống') {
                $similarDocs = $resData['similar_docs'] ?? [];

                $maxScore = collect($similarDocs)->max('similarity_score') ?? 0;
                $score = round($maxScore * 100, 2);

                return redirect()->back()->with('messenge', [
                    'style' => 'danger',
                    'msg' => "Tài liệu đã tồn tại. Độ tương đồng cao nhất: {$score}%"
                ]);
            }

            $vector_path = $resData['vector_path'] ?? null;
        }

        $document->update([
            'title' => $request->title,
            'category_id' => $request->category_id,
            'publisher_id' => $request->publisher_id,
            'cover_image' => $request->cover_image ? $request->cover_image : "/storage/files/1/Avatar/no-image.jpg",
            'file_path' => $new_file_path,
            'file_path_pdf' => $file_path_pdf,
            'vector_path' => $vector_path,
            'file_format' => pathinfo($new_file_path, PATHINFO_EXTENSION),
            'is_free' => $request->is_free,
            'price' => $request->price,
            'description' => $request->description,
            'publication_year' => $request->publication_year,
            'uploaded_by' => Auth::user()->id,
            'status' => 1
        ]);

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
            $transactions = Transaction::where('document_id', $id)->get();
            $hasTransaction = Transaction::where('document_id', $id)->whereIn("type", [2, 4, 5])->exists();

            if ($hasTransaction) {
                return response()->json(['success' => false, 'message' => 'Không thể xóa tài liệu vì đã có giao dịch liên quan']);
            }
            else{
                $destroy->delete();
                $transactions->each(function ($transaction) {
                    $transaction->delete();
                });
                return response()->json(['success' => true, 'message' => 'Xóa tài liệu thành công']);
            }
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

    public function list_approve()
    {
        $items = Document::where("approve", 0)->orderBy('id', 'desc')->get();
        return view('admin.document.approve', compact('items'));
    }

    public function approve($id)
    {
        $item = Document::findOrFail($id);
        $category = DocumentCategory::find($item->category_id);
        $reward = $category->reward;
        $user = User::find($item->uploaded_by);

        $user->point += $reward;
        $user->save();

        $item->approve = 1;
        $item->status = 1;
        $item->save();
        
        Transaction::create([
            'user_id' => $item->uploaded_by,
            'type' => 5,
            'document_id' => $item->id,
            'amount' => $reward,
            'note' => 'Cộng điểm đăng tải tài liệu: ' . $item->title,
        ]);

        return response()->json(['success' => true, 'message' => 'Duyệt tài liệu thành công']);
    }

    public function refuse($id)
    {
        $item = Document::findOrFail($id);

        $old_file_pdf = $item->file_path_pdf;
        $old_vector_path = $item->vector_path;

        if (!empty($old_file_pdf)) {
            $pdfPath = storage_path('app/public/' . $old_file_pdf);
            Log::info('Kiểm tra file PDF', ['pdfPath' => $pdfPath]);
            if (file_exists($pdfPath)) {
                Log::info('Đã xóa file PDF', ['pdfPath' => $pdfPath]);
                unlink($pdfPath);
            }
            $item->file_path_pdf = null;
        }

        if (!empty($old_vector_path)) {
            $vectorPath = storage_path('app/public/' . $old_vector_path);
            Log::info('Kiểm tra thư mục vector', ['vectorPath' => $vectorPath]);
            if (File::isDirectory($vectorPath)) {
                File::deleteDirectory($vectorPath);
                Log::info('Đã xóa thư mục vector', ['vectorPath' => $vectorPath]);
            }
            $item->vector_path = null;
        }

        $item->approve = 2;
        $item->status = 0;
        $item->save();
        Log::info('Đã cập nhật trạng thái document', ['id' => $id, 'approve' => 2, 'status' => 0]);
        return response()->json(['success' => true, 'message' => 'Đã từ chối phê duyệt']);
    }

    public function show($id)
    {
        $item = Document::findOrFail($id);
        return view('admin.document.show', compact('item'));
    }

    public function showApprove($id)
    {
        $item = Document::findOrFail($id);
        return view('admin.document.approve-show', compact('item'));
    }
}
