<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function confirmation(string $orderNumber): View
    {
        $order = Order::where('order_number', $orderNumber)
            ->with(['items.product', 'trackings'])
            ->firstOrFail();

        return view('pages.orders.confirmation', compact('order'));
    }

    public function track(Request $request): View
    {
        $order = null;
        $searched = false;

        if ($request->filled('order_number')) {
            $searched = true;
            $orderNumber = strtoupper(trim($request->input('order_number')));
            $query = Order::where('order_number', $orderNumber)->with(['items.product', 'trackings']);

            if ($request->filled('email')) {
                $email = strtolower(trim($request->input('email')));
                $query->where('customer_email', $email);
            }

            $order = $query->first();
        }

        return view('pages.orders.track', compact('order', 'searched'));
    }
}
