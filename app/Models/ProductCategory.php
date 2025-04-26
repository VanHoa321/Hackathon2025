<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "description",
        "is_active"
    ];

    protected $primaryKey = 'id';
    protected $table = 'product_categories';
    public $timestamps = false;
}
