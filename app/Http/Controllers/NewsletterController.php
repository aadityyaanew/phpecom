<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function subscribe(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'email' => 'required|email|max:255',
        ]);

        NewsletterSubscriber::firstOrCreate(
            ['email' => strtolower(trim($validated['email']))],
            ['is_active' => true]
        );

        $message = "You're subscribed! Use VIP code 'WELCOME10' at checkout for 10% off your first order.";

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'coupon' => 'WELCOME10',
            ]);
        }

        return back()->with('success', $message);
    }
}
