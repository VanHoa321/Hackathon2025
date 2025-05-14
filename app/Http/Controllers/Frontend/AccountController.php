<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Favourite;
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

        // Truy xuất user từ Model User thông qua ID từ Auth
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

        // Truy xuất user từ Model User thông qua ID từ Auth
        $user = User::findOrFail(Auth::id());

        if (!Hash::check($request->old_password, $user->password)) {
            return redirect()->back()
                ->withErrors(['old_password' => 'The old password is incorrect'])
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
        $favourites = Favourite::where('user_id', Auth::id())->with('document')->get();
        return view('frontend.my-favourite', compact('favourites'));
    }

    public function addFavourite(Request $request, $id)
    {
        $user = User::findOrFail(Auth::id());
        $document = Document::findOrFail($id);

        // Check if already favourited
        $existingFavourite = Favourite::where('user_id', $user->id)
            ->where('document_id', $document->id)
            ->first();

        if ($existingFavourite) {
            return response()->json([
                'success' => false,
                'message' => 'Document is already in your favourites!'
            ], 400);
        }

        Favourite::create([
            'user_id' => $user->id,
            'document_id' => $document->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Document added to favourites!'
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
                'message' => 'Document is not in your favourites!'
            ], 400);
        }

        $favourite->delete();

        return response()->json([
            'success' => true,
            'message' => 'Document removed from favourites!'
        ]);
    }
}
