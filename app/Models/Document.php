<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'category_id',
        'publisher_id',
        'cover_image',
        'file_path',
        'file_path_pdf',
        'file_format',
        'is_free',
        'price',
        'description',
        'download_count',
        'view_count',
        'publication_year',
        'created_at',
        'updated_at',
        'uploaded_by',
        'status',
        'approve',
    ];

    protected $primaryKey = 'id';
    protected $table = 'documents';

    public function category()
    {
        return $this->belongsTo(DocumentCategory::class, 'category_id');
    }

    public function publisher()
    {
        return $this->belongsTo(Publisher::class, 'publisher_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function authors()
    {
        return $this->belongsToMany(Author::class, 'document_authors');
    }

    public function comments()
    {
        return $this->hasMany(DocumentComment::class, 'document_id');
    }
    
    public function ratings()
    {
        return $this->hasMany(Rating::class, 'document_id');
    }

    public function upload_by()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'document_id');
    }
}
