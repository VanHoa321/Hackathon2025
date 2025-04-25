<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;

class AdminSiderbarController extends Controller
{
    public function index()
    {
        $items = Menu::with('parents')->orderBy("id", "desc")->get();
        return view("admin.admin-sidebar.index", compact("items"));
    }

    
}
