<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'document_id',
        'rating',
    ];
    protected $table = 'ratings';
    public $timestamps = true;
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function document()
    {
        return $this->belongsTo(Document::class);
    }
    public function scopeAverageRating($query, $documentId)
    {
        return $query->where('document_id', $documentId)->avg('rating');
    }
}
