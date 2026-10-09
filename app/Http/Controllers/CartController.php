<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(
        protected CartService $cart
    ) {}

    public function index(): View
    {
        $summary = $this->cart->getSummary();

        return view('pages.cart.index', compact('summary'));
    }

    public function add(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'nullable|integer|min:1',
            'variant_id' => 'nullable|integer|exists:product_variants,id',
        ]);

        $quantity = $validated['quantity'] ?? 1;
        $variantId = $validated['variant_id'] ?? null;

        $result = $this->cart->addItem($validated['product_id'], $quantity, $variantId);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json($result);
        }

        if (!$result['success']) {
            return back()->with('error', $result['message']);
        }

        return back()->with('success', $result['message']);
    }

    public function update(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'key' => 'required|string',
            'quantity' => 'required|integer|min:0',
        ]);

        $result = $this->cart->updateItem($validated['key'], $validated['quantity']);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json($result);
        }

        if (!$result['success']) {
            return back()->with('error', $result['message']);
        }

        return back()->with('success', $result['message']);
    }

    public function remove(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'key' => 'required|string',
        ]);

        $result = $this->cart->removeItem($validated['key']);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json($result);
        }

        return back()->with('success', $result['message']);
    }

    public function applyCoupon(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50',
        ]);

        $result = $this->cart->applyCoupon($validated['code']);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json($result);
        }

        if (!$result['success']) {
            return back()->with('error', $result['message']);
        }

        return back()->with('success', $result['message']);
    }

    public function removeCoupon(Request $request): JsonResponse|RedirectResponse
    {
        $result = $this->cart->removeCoupon();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json($result);
        }

        return back()->with('success', $result['message']);
    }

    public function summary(): JsonResponse
    {
        return response()->json($this->cart->getSummary());
    }
}
