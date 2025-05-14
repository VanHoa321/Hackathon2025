<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\Favourite;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $item->increment('view_count');
        return view('frontend.document.details', compact('item'));
    }

    public function download($id)
    {
        $document = Document::findOrFail($id);

        if (!$document->is_free) {
            return redirect()->back()->with('error', 'Tài liệu này không miễn phí.');
        }

        $relativePath = $document->file_path;

        $filePath = public_path('storage/' . $relativePath);

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'Tài liệu không tồn tại hoặc đã bị xóa.');
        }

        $document->increment('download_count');

        return response()->download($filePath);
    }

}
