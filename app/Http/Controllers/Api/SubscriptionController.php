<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class SubscriptionController extends Controller
{
    public function status(Request $request): JsonResponse
    {
        return response()->json([
            'is_subscribed' => $request->user()->is_subscribed,
        ]);
    }

    public function subscribe(Request $request): JsonResponse
    {
        $request->user()->update([
            'is_subscribed' => true,
            'subscribed_until' => now()->addMonth(),
        ]);

        return response()->json([
            'is_subscribed' => true,
            'message' => 'Subscription activated (fake Stripe)',
        ]);
    }
}
