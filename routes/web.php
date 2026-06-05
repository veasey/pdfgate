<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Models\User;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('login', function () {
        return view('auth.login');
    })->name('login');

    Route::post('login', function (Request $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors(['email' => 'Invalid credentials'])->onlyInput('email');
        }

        Auth::login($user, true);
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    })->name('login.attempt');
});

Route::post('logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/');
})->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        $totalUsers = User::count();
        $subscribedUsers = User::where('is_subscribed', true)->count();
        $totalPdfCount = User::sum('pdf_generated_count');
        $topUsers = User::orderByDesc('pdf_generated_count')->limit(5)->get();

        return view('dashboard', [
            'totalUsers' => $totalUsers,
            'subscribedUsers' => $subscribedUsers,
            'totalPdfCount' => $totalPdfCount,
            'topUserLabels' => $topUsers->pluck('email'),
            'topUserCounts' => $topUsers->pluck('pdf_generated_count'),
        ]);
    })->name('dashboard');

    Route::get('/pdf-builder', function () {
        return view('pdf.builder');
    })->name('pdf.builder');

    Route::post('/pdf-builder', function (Request $request) {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $user = $request->user();
        $user->increment('pdf_generated_count');
        $user->update(['last_generated_at' => now()]);

        $html = view('pdf.template', $validated)->render();
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html);

        return response($pdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="document.pdf"');
    })->name('pdf.builder.generate');

    Route::get('/admin/users', function () {
        $user = Auth::user();

        if (! $user->is_admin) {
            abort(403);
        }

        return view('admin.users', [
            'users' => User::orderByDesc('pdf_generated_count')->get(),
        ]);
    })->name('admin.users');
});
