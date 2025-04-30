<?php

namespace App\Http\Controllers\ai;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CallApiController extends Controller
{
    public function generatePostContent(Request $request)
    {
        $payload = [
            'title' => $request->input('title'),
            'tags' => $request->input('tags', []),
            'abstract' => $request->input('abstract'),
            'article_objective' => $request->input('purpose'),
            'target_audience' => $request->input('target'),
            'length' => $request->input('length'),
            'tone' => $request->input('tone'),
        ];

        $response = Http::timeout(60)->post('http://127.0.0.1:5000/generate-post', $payload);

        $content = $response->json()['content'] ?? 'Không có nội dung được sinh ra';

        $formatted = nl2br(htmlentities($content));

        return response()->json(['content' => $formatted]);
    }
}
