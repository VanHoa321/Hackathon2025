<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index()
    {
        $items = Post::with('user', 'tags')->orderBy('created_at', 'desc')->get();
        return view('admin.post.index', compact('items'));
    }

    public function create()
    {
        $tags = Tag::where('is_active', 1)->get();
        return view('admin.post.create', compact('tags'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|min:5|max:255',
            'image' => 'required',
            'content' => 'required',
            'tags' => 'required|array',
            'tags.*' => 'exists:tags,id',
        ]);

        $post = new Post();
        $post->title = $request->title;
        $post->image = $request->image;
        $post->content = $request->content;
        $post->abstract = $request->abstract;
        $post->user_id = Auth::id();
        $post->is_active = 1;
        $post->save();
        $post->tags()->sync($request->tags);

        return redirect()->route('admin-post.index')->with('messenge', [
            'style' => 'success',
            'msg' => 'Tạo bài viết thành công!'
        ]);
    }

    public function edit($id)
    {
        $edit = Post::with('tags')->findOrFail($id);
        $tags = Tag::where('is_active', 1)->get();
        return view('admin.post.edit', compact('edit', 'tags'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|min:5|max:255',
            'image' => 'required',
            'content' => 'required',
            'tags' => 'required|array',
            'tags.*' => 'exists:tags,id',
            'abstract' => 'nullable|max:255',
        ]);

        $post = Post::findOrFail($id);
        $post->title = $request->title;
        $post->image = $request->image;
        $post->content = $request->content;
        $post->abstract = $request->abstract;
        $post->user_id = Auth::id();
        $post->save();
        $post->tags()->sync($request->tags);

        return redirect()->route('admin-post.index')
        ->with('messenge', [
            'style' => 'success',
            'msg' => 'Cập nhật bài viết thành công!'
        ]);
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->tags()->detach();
        $post->delete();
        return response()->json([
            'success' => true,
            'message' => 'Xóa bài viết thành công!'
        ]);
    }

    public function changeStatus(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        $post->is_active = !$post->is_active;
        $post->save();

        return response()->json([
            'success' => true,
            'message' => 'Thay đổi trạng thái thành công!'
        ]);
    }
}
