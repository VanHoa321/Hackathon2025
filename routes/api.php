<?php

use App\Http\Controllers\ai\CallApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/ask-document-ai', [CallApiController::class, 'askFromDocument']);
Route::post('/ask-ai', [CallApiController::class, 'askAI']);
Route::post('/ask-ai-admin', [CallApiController::class, 'askAdmin']);
Route::post('/summary', [CallApiController::class, 'summary']);
Route::post('/tts', [CallApiController::class, 'tts']);
Route::post('/generate-post', [CallApiController::class, 'generatePostContent']);
Route::post('/question', [CallApiController::class, 'question']);
