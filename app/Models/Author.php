<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'bio',
        'avatar',
        'birth_date',
        'user_id',
        'approve',
        'is_active',
    ];

    protected $primaryKey = 'id';
    protected $table = 'authors';

    public function documents()
    {
        return $this->belongsToMany(Document::class, 'document_authors');
    }
}
