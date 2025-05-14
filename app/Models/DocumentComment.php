<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentComment extends Model
{
    protected $table = 'document_comments';
    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'document_id',
        'content'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function document()
    {
        return $this->belongsTo(Document::class);
    }
}

