<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Document;
use App\Models\Slide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $slides = Slide::where('is_active', 1)->get();
        $mostViewedDocuments = Document::orderBy('view_count', 'desc')->take(8)->get();
        $mostDownloadedDocuments = Document::orderBy('download_count', 'desc')->take(8)->get();
        $latestDocuments = Document::orderBy('created_at', 'desc')->take(8)->get();
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
