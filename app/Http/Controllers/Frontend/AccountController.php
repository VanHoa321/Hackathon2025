<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\Favourite;
use App\Models\Publisher;
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
                ->withErrors(['old_password' => 'Tài khoản cũ không đúng!'])
                ->withInput();
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->route('frontend.profile')
            ->with('messenge', [
                'style' => 'success',
                'msg' => 'Password updated successfully!'
            ]);
    }

    public function settings()
    {
        return view('frontend.account.settings');
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
        $items = Document::where("uploaded_by", Auth::user()->id)->where("created_at", "desc")->get();
        return view("frontend.account.my-customer", compact("items"));
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

        $relative_path = str_replace('http://127.0.0.1:8000/storage/', '', $full_path);

        $data = [
            'title' => $request->title,
            'category_id' => $request->category_id,
            'publisher_id' => $request->publisher_id,
            'cover_image' => $request->cover_image,
            'file_path' => $relative_path,
            'file_format' => pathinfo($request->file_path, PATHINFO_EXTENSION),
            'is_free' => $request->is_free,
            "price" => $request->price,
            'description' => $request->description,
            'publication_year' => $request->publication_year,
            'uploaded_by' => Auth::user()->id,
            'status' => 0
        ];

        $document = Document::create($data);
        if ($request->has('authors')) {
            $document->authors()->sync($request->authors);
        }
        $request->session()->put("messenge", ["style" => "success", "msg" => "Thêm mới tài liệu thành công"]);
        return redirect()->route("frontend.uploads");
    }
}
