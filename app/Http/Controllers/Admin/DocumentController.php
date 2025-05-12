<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
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
        $publishers = Publisher::all();
        return view('admin.document.create');
    }
}
