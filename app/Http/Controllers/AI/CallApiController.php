<?php

namespace App\Http\Controllers\ai;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CallApiController extends Controller
{

    public function askAI(Request $request)
    {
        $question = $request->input('question');

        $response = Http::post('http://127.0.0.1:5001/ask-ai', [
            'question' => $question
        ]);

        if ($response->failed()) {
            Log::error('Flask API Error: ' . $response->body());
            return response()->json(['answer' => 'Không có phản hồi từ AI']);
        }

        $answer = $response->json()['answer'] ?? 'Không có phản hồi';

        $formattedAnswer = nl2br(htmlentities($answer));

        return response()->json(['question' => $question, 'answer' => $formattedAnswer]);
    }

    public function askAdmin(Request $request)
    {
        $question = $request->input('question');

        $response = Http::post('http://127.0.0.1:5008/ask-ai-admin', [
            'question' => $question
        ]);

        if ($response->failed()) {
            Log::error('Flask API Error: ' . $response->body());
            return response()->json(['answer' => 'Không có phản hồi từ AI']);
        }

        $answer = $response->json()['answer'] ?? 'Không có phản hồi';
        $chart = $response->json()['chart'] ?? null;

        Log::info('Flask API Response: ' . $answer);
        Log::info('Flask API Response: ' . json_encode($response->json(), JSON_UNESCAPED_UNICODE));

        $formattedAnswer = nl2br(htmlentities($answer));

        return response()->json(['question' => $question, 'answer' => $formattedAnswer, 'chart' => $chart]);
    }
    
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

    public function askFromDocument(Request $request)
    {
        $id = $request->input('id');
        $question = $request->input('question');
        $document = Document::findOrFail($id);

        $vector_path  = storage_path('app/public/' . $document->vector_path);
        Log::info('Vector path: ' . $vector_path);

        $response = Http::timeout(30)->post('http://127.0.0.1:5002/ask', [
            'question' => $question,
            'vector_path' => $vector_path,
        ]);

        if ($response->failed()) {
            Log::error('Flask API Error: ' . $response->body());
            return response()->json(['answer' => 'Không có phản hồi từ AI']);
        }

        $answer = $response->json()['answer'] ?? 'Không có phản hồi';
        $formattedAnswer = nl2br(htmlentities($answer));

        return response()->json(['answer' => $formattedAnswer]);
    }

    public function summary(Request $request)
    {
        $id = $request->input('id');
        $document = Document::findOrFail($id);
        $file_path = $document->file_path;

        if ($document->file_path_pdf) 
        {
            $full_path = storage_path('app/public/' . $document->file_path_pdf);
        }
        else 
        {
            $full_path = storage_path('app/public/' . $file_path);
        }

        $response = Http::timeout(30)->post('http://127.0.0.1:5003/summarize', [
            'file_path' => $full_path,
        ]);

        if ($response->failed()) {
            Log::error('Flask API Error: ' . $response->body());
            return response()->json(['answer' => 'Không có phản hồi từ AI']);
        }

        $data = $response->json();
        $answer = 'Không tóm tắt được nội dung tài liệu này.';

        if (isset($data['summary_full'])) {
            $answer = $data['summary_full'];
        } elseif (isset($data['summary_by_chapter'])) {
            $chapters = $data['summary_by_chapter'];
            $summaryParts = [];
            foreach ($chapters as $chapter => $summary) {
                $summaryParts[] = "<strong>$chapter</strong>:<br>" . nl2br(htmlentities($summary));
            }
            $answer = implode('<br><br>', $summaryParts);
        }

        $formattedAnswer = nl2br($answer);

        return response()->json(['answer' => $formattedAnswer]);
    }

    public function tts(Request $request)
    {
        $id = $request->input('id');
        $document = Document::findOrFail($id);
        $file_path = $document->file_path;

        if ($document->file_path_pdf) 
        {
            $full_path = storage_path('app/public/' . $document->file_path_pdf);
        }
        else 
        {
            $full_path = storage_path('app/public/' . $file_path);
        }

        $response = Http::timeout(30)->post('http://127.0.0.1:5004/tts', [
            'file_path' => $full_path,
        ]);

        if ($response->failed()) {
            Log::error('Flask API Error: ' . $response->body());
            return response()->json(['answer' => 'Không có phản hồi từ AI']);
        }

        $audioContent = $response->body();
        return response($audioContent, 200)->header('Content-Type', 'audio/mpeg');
    }

    public function question(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        try {
            $image = $request->file('image');
            
            $response = Http::attach(
                'image', 
                file_get_contents($image->getRealPath()),
                $image->getClientOriginalName()
            )->post('http://localhost:5006/question', [
                'input_text' => $request->input('text', 'Hãy phân tích hình ảnh để tìm thông tin về tài liệu hoặc sách trong đó.')
            ]);

            return $response->json();

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Lỗi hệ thống: ' . $e->getMessage()
            ], 500);
        }
    }
}
