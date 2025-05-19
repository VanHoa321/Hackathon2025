<?php
namespace App\Http\Controllers\frontend;

use App\Models\Author;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function index()
    {
        $authors = Author::where('is_active', 1)
            ->where('approve', 1)
            ->orderBy('created_at', 'desc')
            ->paginate(8);
        return view('frontend.author.index', compact('authors'));
    }
    public function show($id)
    {
        $author = Author::with('documents')->findOrFail($id);
        $documents = $author->documents()->paginate(9);
        return view('frontend.author.detail', compact('author', 'documents'));
    }
}
?>
