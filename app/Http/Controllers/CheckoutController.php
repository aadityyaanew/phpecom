<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use App\Services\CheckoutService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(
        protected CartService $cart,
        protected CheckoutService $checkout
    ) {}

    public function index(): View|RedirectResponse
    {
        $summary = $this->cart->getSummary();
        if ($summary['items_count'] === 0) {
            return redirect()->route('cart.index')->with('info', 'Your bag is empty. Please add items before proceeding to checkout.');
        }

        $user = Auth::user();
        $defaultAddress = $user?->addresses()->where('is_default', true)->first() ?? $user?->addresses()->first();

        return view('pages.checkout.index', compact('summary', 'user', 'defaultAddress'));
    }

    public function updateShippingMethod(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'shipping_method' => 'required|in:standard,express,priority',
        ]);

        $this->cart->setShippingMethod($validated['shipping_method']);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'summary' => $this->cart->getSummary(),
            ]);
        }

        return back();
    }

    public function process(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'phone' => 'nullable|string|max:30',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'same_as_shipping' => 'nullable|boolean',
            'payment_method' => 'required|in:card,cod,paypal,bank_transfer',
            'card_number' => 'nullable|required_if:payment_method,card|string',
            'card_expiry' => 'nullable|required_if:payment_method,card|string',
            'card_cvc' => 'nullable|required_if:payment_method,card|string',
            'notes' => 'nullable|string|max:500',
        ]);

        $result = $this->checkout->processOrder($validated);

        if (!$result['success']) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json($result, 422);
            }
            return back()->withInput()->with('error', $result['message']);
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'redirect_url' => route('orders.confirmation', ['order_number' => $result['order']->order_number]),
            ]);
        }

        return redirect()->route('orders.confirmation', ['order_number' => $result['order']->order_number]);
    }
}
