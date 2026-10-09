<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $categories = Category::active()
            ->featured()
            ->withCount('products')
            ->orderBy('sort_order')
            ->take(6)
            ->get();

        $featuredProducts = Product::active()
            ->featured()
            ->with(['brand', 'categories'])
            ->take(8)
            ->get();

        $bestsellers = Product::active()
            ->bestseller()
            ->with(['brand', 'categories'])
            ->take(4)
            ->get();

        $newArrivals = Product::active()
            ->with(['brand', 'categories'])
            ->latest()
            ->take(4)
            ->get();

        $brands = Brand::where('is_featured', true)->get();

        $reviews = Review::where('status', 'approved')
            ->where('rating', '>=', 4)
            ->with('product')
            ->latest()
            ->take(6)
            ->get();

        return view('pages.home', compact(
            'categories',
            'featuredProducts',
            'bestsellers',
            'newArrivals',
            'brands',
            'reviews'
        ));
    }
}
