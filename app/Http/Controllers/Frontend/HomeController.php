<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Document;
use App\Models\Favourite;
use App\Models\Slide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $slides = Slide::where('is_active', 1)->get();

        $mostViewedDocuments = Document::orderBy('view_count', 'desc')->take(8)->get()->map(function ($mostViewedDocuments) {
            $mostViewedDocuments->favourited_by_user = Auth::check() && Favourite::where('user_id', Auth::id())
                ->where('document_id', $mostViewedDocuments->id)
                ->exists();
            return $mostViewedDocuments;
        });

        $mostDownloadedDocuments = Document::orderBy('download_count', 'desc')->take(8)->get()->map(function ($mostDownloadedDocuments) {
            $mostDownloadedDocuments->favourited_by_user = Auth::check() && Favourite::where('user_id', Auth::id())
                ->where('document_id', $mostDownloadedDocuments->id)
                ->exists();
            return $mostDownloadedDocuments;
        });

        $latestDocuments = Document::orderBy('created_at', 'desc')->take(8)->get()->map(function ($latestDocuments) {
            $latestDocuments->favourited_by_user = Auth::check() && Favourite::where('user_id', Auth::id())
                ->where('document_id', $latestDocuments->id)
                ->exists();
            return $latestDocuments;
        });

        return view('frontend.home.index', compact('slides', 'mostViewedDocuments', 'mostDownloadedDocuments', 'latestDocuments'));
    }

    public function about()
    {
        return view('frontend.home.about-us');
    }

    public function contact()
    {
        return view('frontend.home.contact-us');
    }
    
    public function sendContact(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        Contact::create([
            'user_id' => Auth::id(),
            'message' => $request->message,
            'is_read' => 0,
        ]);

        return redirect()->back()->with('success', 'Liên hệ của bạn đã được gửi thành công!');
    }
}
