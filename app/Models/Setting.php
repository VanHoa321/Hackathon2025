<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        "max_login", 
        "home_doc_new",
        "home_doc_download",
        "home_doc_view",
        "home_post",
        "doc_page",
        "doc_preview"
    ];

    protected $table = 'settings';
    public $timestamps = false;
}
