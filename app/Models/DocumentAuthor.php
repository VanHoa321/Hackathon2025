<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentAuthor extends Model
{
    use HasFactory;

    protected $fillable = [
        "document_id",
        "author_id",
    ];

    protected $primaryKey = 'id';
    protected $table = 'document_authors';
    public $timestamps = false;
}
