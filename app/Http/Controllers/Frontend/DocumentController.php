<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\DocumentComment;
use App\Models\Favourite;
use App\Models\Publisher;
use App\Models\Rating;
use App\Models\Transaction;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index()
    {
        $categories = DocumentCategory::withCount('documents')->where('is_active', 1)->get();
        $publishers = Publisher::withCount('documents')->where('is_active', 1)->get();
        return view('frontend.document.index', compact('categories', 'publishers'));
    }

    public function getData(Request $request)
    {
        $search_name = $request->search_name;

        $per_page = 8;
        $current_page = $request->input('page', 1);

        $query = Document::where('status', 1);

        if (!empty($search_name)) {
            $query->where('title', 'LIKE', '%' . $search_name . '%');
        }

        if ($request->has('category_ids')) {
            $query->whereIn('category_id', $request->category_ids);
        }

        if ($request->has('publisher_ids')) {
            $query->whereIn('publisher_id', $request->publisher_ids);
        }

        if ($request->has('free_ids')) {
            $query->whereIn('is_free', $request->free_ids);
        }

        $query->orderBy('created_at', 'desc');
        $total_documents = $query->count();
        $documents = $query->skip(($current_page - 1) * $per_page)->take($per_page)->get();

        $documents = $documents->map(function ($doc) {
            $doc->favourited_by_user = Auth::check() && Favourite::where('user_id', Auth::id())
                ->where('document_id', $doc->id)
                ->exists();
            $doc->average_rating = Rating::where('document_id', $doc->id)->avg('rating') ?? 0;
            $doc->rating_count = Rating::where('document_id', $doc->id)->count();
            return $doc;
        });

        $last_page = ceil($total_documents / $per_page);

        return response()->json([
            'documents' => $documents,
            'current_page' => $current_page,
            'last_page' => $last_page,
            'prev_page_url' => $current_page > 1 ? url()->current() . '?page=' . ($current_page - 1) : null,
            'next_page_url' => $current_page < $last_page ? url()->current() . '?page=' . ($last_page + 1) : null,
        ]);
    }

    public function details($id)
    {
        $item = Document::with('category', 'publisher', 'authors')->find($id);
        $comments = DocumentComment::where('document_id', $id)->orderBy('created_at', 'desc')->get();
        $averageRating = Rating::where('document_id', $id)->avg('rating') ?? 0;
        $ratingCount = Rating::where('document_id', $id)->count();
        $userRating = Auth::check() ? Rating::where('document_id', $id)->where('user_id', Auth::id())->first() : null;

        $hasAccess = $item->is_free || (Auth::check() && $this->hasPurchasedDocument(Auth::id(), $id));

        $item->increment('view_count');

         $client = new Client();
        $recommendations = [];

        try {
            $response = $client->get("http://127.0.0.1:5005/recommend/{$id}");
            $data = json_decode($response->getBody(), true);
            $recommendedIds = $data['recommendations'] ?? [];

            // Query database for recommended doc
            if (!empty($recommendedIds)) {
                $recommendations = Document::whereIn('id', $recommendedIds)
                    ->with(['category', 'authors'])
                    ->get()->map(function ($doc) {
                        $doc->favourited_by_user = Auth::check() && Favourite::where('user_id', Auth::id())
                            ->where('document_id', $doc->id)
                            ->exists();
                        $doc->average_rating = Rating::where('document_id', $doc->id)->avg('rating') ?? 0;
                        $doc->rating_count = Rating::where('document_id', $doc->id)->count();
                        return $doc;
                    });
            }
        } catch (RequestException $e) {
            Log::error("Failed to fetch recommendations: {$e->getMessage()}");
        }

        return view('frontend.document.details', compact('item', 'comments', 'ratingCount', 'averageRating', 'userRating', 'hasAccess', 'recommendations'));
    }

    public function comment(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['status' => 'unauthenticated'], 401);
        }

        $request->validate([
            'document_id' => 'required|exists:documents,id',
            'content' => 'required|string|max:1000',
        ]);

        $comment = new DocumentComment();
        $comment->document_id = $request->document_id;
        $comment->user_id = Auth::id();
        $comment->content = $request->content;
        $comment->created_at = now();
        $comment->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Đã bình luận!',
            'comment' => [
                'user_name' => Auth::user()->name,
                'avatar' => Auth::user()->avatar,
                'created_at' => $comment->created_at,
                'content' => $comment->content
            ]
        ]);
    }

    public function download($id)
    {
        $document = Document::findOrFail($id);

        if (!$document->is_free && (!Auth::check() || !$this->hasPurchasedDocument(Auth::id(), $id))) {
            return redirect()->back()->with('error', 'Bạn cần mua tài liệu này để tải xuống!');
        }

        if (!$document->is_free) {
            return redirect()->back()->with('error', 'Tài liệu này không miễn phí!');
        }

        $relativePath = $document->file_path;

        $filePath = public_path('storage/' . $relativePath);

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'Tài liệu không tồn tại hoặc đã bị xóa!');
        }

        $document->increment('download_count');

        Transaction::create([
            'user_id' => Auth::user()->id,
            'type' => 4,
            'document_id' => $document->id,
            'note' => 'Tải xuống tài liệu: ' . $document->title,
        ]);

        return response()->download($filePath);
    }

    public function downloadPDF($id)
    {
        $document = Document::findOrFail($id);

        if (!$document->is_free && (!Auth::check() || !$this->hasPurchasedDocument(Auth::id(), $id))) {
            return redirect()->back()->with('error', 'Bạn cần mua tài liệu này để tải xuống!');
        }

        if (!$document->is_free) {
            return redirect()->back()->with('error', 'Tài liệu này không miễn phí!');
        }

        $relativePath = $document->file_path_pdf;

        $filePath = public_path('storage/' . $relativePath);

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'Tài liệu không tồn tại hoặc đã bị xóa!');
        }

        $document->increment('download_count');

        return response()->download($filePath);
    }

    public function rate(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|numeric|min:0|max:10',
        ]);

        $doc = Document::findOrFail($id);
        $user = auth()->user();

        // Tạo hoặc cập nhật đánh giá
        $doc->ratings()->updateOrCreate(
            ['user_id' => $user->id],
            ['rating' => $request->rating]
        );

        return response()->json(['message' => 'Đánh giá của bạn đã được lưu.']);
    }

    public function unrate($filmId)
    {
        $doc = Document::findOrFail($filmId);
        $user = auth()->user();

        // Xóa đánh giá
        $doc->ratings()->where('user_id', $user->id)->delete();

        return response()->json(['message' => 'Đánh giá của bạn đã được xóa.']);
    }

    private function hasPurchasedDocument($userId, $documentId)
    {
        return Transaction::where('user_id', $userId)
            ->where('document_id', $documentId)
            ->where('type', 2)
            ->exists();
    }

    public function purchase($id)
    {
        $document = Document::findOrFail($id);

        if (Auth::check() && $this->hasPurchasedDocument(Auth::id(), $id)) {
            return redirect()->route('frontend.document.details', $id)
                ->with('error', 'Bạn đã mua tài liệu này rồi!');
        }

        if (Auth::user()->point < $document->price) {
            return redirect()->route('frontend.document.details', $id)
                ->with('error', 'Số tiền không đủ để mua!');
        }

        Transaction::create([
            'user_id' => Auth::id(),
            'type' => 2,
            'amount' => $document->price,
            'document_id' => $document->id,
            'note' => 'Mua tài liệu: ' . $document->title,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $user = User::find(Auth::user()->id);
        $user->point -= $document->price;
        $user->save();

        return redirect()->route('frontend.document.details', $id)->with('success', 'Mua tài liệu thành công! Bạn có thể tải xuống và sử dụng đầy đủ tính năng.');
    }
}
