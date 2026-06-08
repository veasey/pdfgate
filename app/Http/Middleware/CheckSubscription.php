<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    public function handle(Request $request, Closure $next): mixed
    {
        if (! $request->user()?->is_subscribed) {
            return response()->json([
                'message' => 'Subscription required.',
            ], Response::HTTP_PAYMENT_REQUIRED);
        }

        return $next($request);
    }
}
