<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        $items = Transaction::orderBy("created_at", "desc")->get();
        return view("admin.transaction.index", compact("items"));
    }
}
