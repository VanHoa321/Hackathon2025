<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "category_id",
        "image",
        "price",
        "sale_price",
        "abstract",
        "content",
        "is_active"
    ];

    protected $primaryKey = 'id';
    protected $table = 'products';

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }
}
