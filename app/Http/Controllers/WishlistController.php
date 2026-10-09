<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use App\Services\WishlistService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WishlistController extends Controller
{
    public function __construct(
        protected WishlistService $wishlist,
        protected CartService $cart
    ) {}

    public function index(): View
    {
        $products = $this->wishlist->getItems();

        return view('pages.wishlist.index', compact('products'));
    }

    public function toggle(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
        ]);

        $result = $this->wishlist->toggle($validated['product_id']);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json($result);
        }

        return back()->with('success', $result['message']);
    }

    public function moveToCart(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
        ]);

        $this->cart->addItem($validated['product_id'], 1);
        $this->wishlist->toggle($validated['product_id']);

        return back()->with('success', 'Item moved to your bag.');
    }
}
