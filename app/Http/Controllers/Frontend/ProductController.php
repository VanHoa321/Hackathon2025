<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $categories = ProductCategory::where('is_active', 1)->get();
        return view('frontend.product.index', compact('categories'));
    }

    public function getData(Request $request)
    {
        $categoryId = $request->category_id;
        $searchName = $request->search_name;
        $priceRanges = $request->input('price_ranges', []);
        $perPage = 6;
        $currentPage = $request->input('page', 1);

        $query = Product::where('is_active', 1);

        if (!empty($categoryId) && $categoryId !== 'all') {
            $query->where('category_id', $categoryId);
        }

        if (!empty($searchName)) {
            $query->where('name', 'LIKE', '%' . $searchName . '%');
        }

        if (!empty($priceRanges)) {
            $query->where(function ($q) use ($priceRanges) {
                foreach ($priceRanges as $range) {
                    switch ($range) {
                        case 'under_100k':
                            $q->orWhere('sale_price', '<', 100000);
                            break;
                        case '100k_to_500k':
                            $q->orWhereBetween('sale_price', [100000, 500000]);
                            break;
                        case '500k_to_1m':
                            $q->orWhereBetween('sale_price', [500000, 1000000]);
                            break;
                        case 'above_1m':
                            $q->orWhere('sale_price', '>', 1000000);
                            break;
                    }
                }
            });
        }

        $query->orderBy('created_at', 'desc');
        $totalProducts = $query->count();
        $products = $query->skip(($currentPage - 1) * $perPage)->take($perPage)->get();
        $lastPage = ceil($totalProducts / $perPage);

        return response()->json([
            'products' => $products,
            'current_page' => $currentPage,
            'last_page' => $lastPage,
            'prev_page_url' => $currentPage > 1 ? url()->current() . '?page=' . ($currentPage - 1) : null,
            'next_page_url' => $currentPage < $lastPage ? url()->current() . '?page=' . ($currentPage + 1) : null,
        ]);
    }
}
