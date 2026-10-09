<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::active()->with(['brand', 'categories']);

        // Search
        if ($request->filled('q')) {
            $query->search($request->input('q'));
        }

        // Category filter
        if ($request->filled('category')) {
            $categorySlug = $request->input('category');
            $query->whereHas('categories', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        // Brand filter
        if ($request->filled('brand')) {
            $brandSlug = $request->input('brand');
            $query->whereHas('brand', function ($q) use ($brandSlug) {
                $q->where('slug', $brandSlug);
            });
        }

        // Price range
        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float) $request->input('min_price'));
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float) $request->input('max_price'));
        }

        // In Stock filter
        if ($request->boolean('in_stock')) {
            $query->inStock();
        }

        // On Sale filter
        if ($request->boolean('on_sale')) {
            $query->whereNotNull('compare_at_price')->whereRaw('compare_at_price > price');
        }

        // Sorting
        $sort = $request->input('sort', 'featured');
        match ($sort) {
            'newest' => $query->latest(),
            'price_asc' => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'rating' => $query->orderBy('rating_average', 'desc'),
            default => $query->orderBy('is_featured', 'desc')->latest(),
        };

        $products = $query->paginate(12)->withQueryString();

        $categories = Category::active()->withCount('products')->orderBy('name')->get();
        $brands = Brand::withCount('products')->orderBy('name')->get();

        $activeCategory = $request->filled('category') ? Category::where('slug', $request->input('category'))->first() : null;
        $activeBrand = $request->filled('brand') ? Brand::where('slug', $request->input('brand'))->first() : null;

        return view('pages.products.index', compact(
            'products',
            'categories',
            'brands',
            'activeCategory',
            'activeBrand'
        ));
    }

    public function show(string $slug): View
    {
        $product = Product::active()
            ->where('slug', $slug)
            ->with(['brand', 'categories', 'variants', 'approvedReviews.user'])
            ->firstOrFail();

        $relatedProducts = Product::active()
            ->where('id', '!=', $product->id)
            ->where(function ($q) use ($product) {
                $categoryIds = $product->categories->pluck('id');
                if ($categoryIds->isNotEmpty()) {
                    $q->whereHas('categories', fn ($cat) => $cat->whereIn('categories.id', $categoryIds));
                }
                if ($product->brand_id) {
                    $q->orWhere('brand_id', $product->brand_id);
                }
            })
            ->take(4)
            ->get();

        if ($relatedProducts->isEmpty()) {
            $relatedProducts = Product::active()->where('id', '!=', $product->id)->take(4)->get();
        }

        // Ratings breakdown calculation
        $totalReviews = $product->approvedReviews->count();
        $ratingDistribution = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
        foreach ($product->approvedReviews as $r) {
            if (isset($ratingDistribution[$r->rating])) {
                $ratingDistribution[$r->rating]++;
            }
        }

        return view('pages.products.show', compact(
            'product',
            'relatedProducts',
            'totalReviews',
            'ratingDistribution'
        ));
    }

    public function storeReview(Request $request, string $slug): RedirectResponse
    {
        $product = Product::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'customer_name' => 'required|string|max:100',
            'customer_email' => 'required|email|max:150',
            'title' => 'nullable|string|max:150',
            'comment' => 'required|string|min:10|max:2000',
        ]);

        $review = Review::create([
            'product_id' => $product->id,
            'user_id' => auth()->id(),
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'rating' => $validated['rating'],
            'title' => $validated['title'] ?? null,
            'comment' => $validated['comment'],
            'status' => 'approved',
            'is_verified_purchase' => true,
        ]);

        return back()->with('success', 'Thank you! Your verified review has been published.');
    }
}
