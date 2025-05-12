<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Publisher;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function index()
    {
        return view('admin.document.index');
    }

    public function create()
    {
        $publishers = Publisher::where("is_active", 1)->get();
        $categories = Document::where("is_active", 1)->get();
        return view('admin.document.create', compact('publishers', 'categories'));
    }
}
