<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\Favourite;
use App\Models\Publisher;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    public function profile()
    {
        return view('frontend.account.profile');
    }

    public function editProfile()
    {
        return view('frontend.account.profile');
    }

    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'avatar' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::findOrFail(Auth::id());
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'description' => $request->description,
            'avatar' => $request->avatar,
        ]);

        return redirect()->route('frontend.profile')
            ->with('messenge', [
                'style' => 'success',
                'msg' => 'Profile updated successfully!'
            ]);
    }

    public function editPassword()
    {
        return view('frontend.account.edit-password');
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::findOrFail(Auth::id());

        if (!Hash::check($request->old_password, $user->password)) {
            return redirect()->back()
                ->withErrors(['old_password' => 'Mật khẩu cũ không đúng!'])
                ->withInput();
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->route('frontend.profile')
            ->with('messenge', [
                'style' => 'success',
                'msg' => 'Đổi mật khẩu thành công!'
            ]);
    }

     public function myFavourite()
    {
        $favourites = Favourite::where('user_id', Auth::id())->with('document')->get()->map(function ($favourites) {
            $favourites->favourited_by_user = Auth::check() && Favourite::where('user_id', Auth::id())
                ->where('document_id', $favourites->id)
                ->exists();
            return $favourites;
        });
        return view('frontend.account.my-favourite', compact('favourites'));
    }

    public function addFavourite(Request $request, $id)
    {
        $user = User::findOrFail(Auth::id());
        $document = Document::findOrFail($id);

        $existingFavourite = Favourite::where('user_id', $user->id)
            ->where('document_id', $document->id)
            ->first();

        if ($existingFavourite) {
            return response()->json([
                'success' => false,
                'message' => 'Tài liệu này đã tồn tại trong danh sách yêu thích của bạn!'
            ], 400);
        }

        Favourite::create([
            'user_id' => $user->id,
            'document_id' => $document->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tài liệu đã được thêm vào danh sách yêu thích!'
        ]);
    }

    public function removeFavourite(Request $request, $id)
    {
        $user = User::findOrFail(Auth::id());
        $document = Document::findOrFail($id);

        $favourite = Favourite::where('user_id', $user->id)
            ->where('document_id', $document->id)
            ->first();

        if (!$favourite) {
            return response()->json([
                'success' => false,
                'message' => 'Tài liệu này không tồn tại trong danh sách yêu thích của bạn!'
            ], 400);
        }

        $favourite->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tài liệu đã được xóa khỏi danh sách yêu thích!'
        ]);
    }

    public function myDocument()
    {
        $items = Document::where("uploaded_by", Auth::user()->id)->orderBy("created_at", "desc")->get();
        return view("frontend.account.my-document", compact("items"));
    }

    public function uploads()
    {
        $publishers = Publisher::where("is_active", 1)->get();
        $categories = DocumentCategory::where("is_active", 1)->orderBy("id", "asc")->get();
        $authors = Author::where("is_active", 1)->orderBy("id", "asc")->get();
        return view('frontend.account.upload', compact('publishers', 'categories', 'authors'));
    }

    public function postUpload(Request $request)
    {
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

        $data = [
            'title' => $request->title,
            'category_id' => $request->category_id,
            'publisher_id' => 1,
            'cover_image' => $request->cover_image ? $request->cover_image : "/storage/files/1/Avatar/no-image.jpg",
            'file_path' => $relative_path,
            'file_path_pdf' => $file_path_pdf,
            'file_format' => $file_extension,
            'is_free' => $request->is_free,
            'price' =>$request->price,
            'description' => $request->description,
            'uploaded_by' => Auth::user()->id,
            'status' => 0,
            'approve' => 0
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
        return redirect()->route("frontend.mydocument");
    }

    public function destroy(string $id)
    {
        $destroy = Document::find($id);
        if ($destroy) {
            $transactions = $destroy->transactions;
            if ($transactions->where('type', '!=', 3)->count() > 0) {
                return response()->json(['danger' => false, 'message' => 'Không thể xóa tài liệu vì đã có giao dịch liên quan khác loại 3'], 403);
            }
            else {
                $destroy->delete();
                $transactions->each(function ($transaction) {
                    $transaction->delete();
                });
                return response()->json(['danger' => true, 'message' => 'Xóa tài liệu thành công'], 200);
            }
        } else {
            return response()->json(['danger' => false, 'message' => 'Tài liệu không tồn tại'], 404);
        }
    }

    public function indexPoint()
    {
        return view("frontend.account.point");
    }

    public function vnpay_payment(Request $request)
    {
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = route('vnpay.return');
        $vnp_TmnCode = "F23SUP2A";
        $vnp_HashSecret = "JB7QJNX2P9Z3JXTX8X8Q4ADXIS1QHSY3"; 

        $vnp_TxnRef = time(); 
        $vnp_OrderInfo = "Nạp " . $request->point . " VNĐ vào tài khoản";
        $vnp_OrderType = "Deposit";
        $vnp_Amount = $request->point * 100;
        $vnp_Locale = "vn";
        $vnp_BankCode = "NCB";
        $vnp_IpAddr = $request->ip();

        $inputData = [
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => now()->format('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        ];

        if (!empty($vnp_BankCode)) {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }

        ksort($inputData);
        $query = "";
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }
        $query = trim($query, "&");

        $vnp_Url .= "?" . $query;
        $vnpSecureHash = hash_hmac('sha512', ltrim($hashdata, '&'), $vnp_HashSecret);
        $vnp_Url .= '&vnp_SecureHash=' . $vnpSecureHash;

        return redirect($vnp_Url);
    }

    public function vnpayReturn(Request $request)
    {
        $vnp_ResponseCode = $request->get('vnp_ResponseCode');
        $vnp_Amount = $request->get('vnp_Amount') / 100000;

        $user = User::find(Auth::user()->id);

        if ($vnp_ResponseCode == '24') {
            return redirect('/account/point');
        }

        if ($vnp_ResponseCode == '00') {
            
            Transaction::create([
                'user_id'     => $user->id,
                'type'        => 1,
                'amount'      => $vnp_Amount,
                'document_id' => null,
                'note'        => 'Nạp điểm vào tài khoản qua VNPAY',
            ]);

            $user->point += $vnp_Amount;
            $user->save();
            return redirect()->route('frontend.success')->with('amount', $vnp_Amount);
        }
    }

    public function vnpSuccess()
    {
        return view("frontend.account.success");
    }

    public function tranHistory()
    {
        $transactions = Transaction::where('user_id', Auth::user()->id)
            ->with('document')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('frontend.account.tran-history', compact('transactions'));
    }

}
