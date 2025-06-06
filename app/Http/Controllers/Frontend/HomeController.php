<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Contact;
use App\Models\Document;
use App\Models\Favourite;
use App\Models\Post;
use App\Models\Rating;
use App\Models\Setting;
use App\Models\Slide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $setting = Setting::first();

        $slides = Slide::where('is_active', 1)->get();

        $mostViewedDocuments = Document::where("status", 1)->orderBy('view_count', 'desc')->take($setting->home_doc_view ?? 8)->get()->map(function ($mostViewedDocuments) {
            $mostViewedDocuments->favourited_by_user = Auth::check() && Favourite::where('user_id', Auth::id())
                ->where('document_id', $mostViewedDocuments->id)
                ->exists();
            $mostViewedDocuments->average_rating = Rating::where('document_id', $mostViewedDocuments->id)->avg('rating') ?? 0;
            $mostViewedDocuments->rating_count = Rating::where('document_id', $mostViewedDocuments->id)->count();
            return $mostViewedDocuments;
        });

        $mostDownloadedDocuments = Document::where("status", 1)->orderBy('download_count', 'desc')->take($setting->home_doc_download ?? 8)->get()->map(function ($mostDownloadedDocuments) {
            $mostDownloadedDocuments->favourited_by_user = Auth::check() && Favourite::where('user_id', Auth::id())
                ->where('document_id', $mostDownloadedDocuments->id)
                ->exists();
            $mostDownloadedDocuments->average_rating = Rating::where('document_id', $mostDownloadedDocuments->id)->avg('rating') ?? 0;
            $mostDownloadedDocuments->rating_count = Rating::where('document_id', $mostDownloadedDocuments->id)->count();
            return $mostDownloadedDocuments;
        });

        $latestDocuments = Document::where("status", 1)->orderBy('created_at', 'desc')->take($setting->home_doc_new ?? 8)->get()->map(function ($latestDocuments) {
            $latestDocuments->favourited_by_user = Auth::check() && Favourite::where('user_id', Auth::id())
                ->where('document_id', $latestDocuments->id)
                ->exists();
            $latestDocuments->average_rating = Rating::where('document_id', $latestDocuments->id)->avg('rating') ?? 0;
            $latestDocuments->rating_count = Rating::where('document_id', $latestDocuments->id)->count();
            return $latestDocuments;
        });

        $posts = Post::where("is_active", 1)->orderBy("created_at", "desc")->take(3)->get();

        return view('frontend.home.index', compact('slides', 'mostViewedDocuments', 'mostDownloadedDocuments', 'latestDocuments', 'posts'));
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
