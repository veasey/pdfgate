<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', function (Request $request) {
    $validated = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (!auth()->attempt($validated)) {
        return response()->json([
            'message' => 'Invalid credentials',
        ], 401);
    }

    $user = auth()->user();
    $token = $user->createToken('api-token')->plainTextToken;

    return response()->json([
        'token' => $token,
        'user' => $user,
    ]);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', function (Request $request) {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    });

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/tokens', function (Request $request) {
        $token = $request->user()->createToken(
            $request->input('name', 'api-token'),
            $request->input('abilities', ['*'])
        );

        return response()->json([
            'token' => $token->plainTextToken,
        ]);
    });

    Route::get('/subscription', function (Request $request) {
        return response()->json([
            'is_subscribed' => $request->user()->is_subscribed,
        ]);
    });

    Route::post('/subscribe', function (Request $request) {
        $request->user()->update(['is_subscribed' => true]);

        return response()->json([
            'is_subscribed' => true,
            'message' => 'Subscription activated (fake Stripe)',
        ]);
    });

    Route::post('/pdf', function (Request $request) {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $html = view('pdf.template', $validated)->render();
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html);

        return response($pdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="document.pdf"');
    });

    Route::delete('/tokens/{token_id}', function (Request $request, $token_id) {
        $request->user()->tokens()->where('id', $token_id)->delete();

        return response()->json([
            'message' => 'Token revoked successfully',
        ]);
    });
});
