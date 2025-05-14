<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "description",
        "is_active"
    ];

    protected $primaryKey = 'id';
    protected $table = 'document_categories';
    public $timestamps = false;

    public function documents()
    {
        return $this->hasMany(Document::class, 'category_id');
    }
}
