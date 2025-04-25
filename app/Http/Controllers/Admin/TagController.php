<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index()
    {
        $items = Tag::orderBy("id", "desc")->get();
        return view("admin.tag.index", compact("items"));
    }
}
