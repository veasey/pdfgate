<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PDFGate</title>
    <style>
        body { margin: 0; min-height: 100vh; display: flex; align-items: center; justify-content: center; font-family: Inter, system-ui, sans-serif; background: #eef2ff; color: #111827; }
        .card { width: min(520px, 96vw); padding: 32px; border-radius: 24px; background: white; box-shadow: 0 24px 80px rgba(15, 23, 42, 0.12); }
        h1 { margin: 0 0 18px; font-size: 1.75rem; }
        label { display: block; margin-bottom: 8px; font-weight: 700; }
        input { width: 100%; border: 1px solid #d1d5db; border-radius: 14px; padding: 14px 16px; font-size: 1rem; }
        button { margin-top: 20px; width: 100%; border: none; border-radius: 14px; background: #2563eb; color: white; padding: 14px 16px; font-size: 1rem; font-weight: 700; cursor: pointer; }
        .note { margin-top: 16px; color: #4b5563; font-size: 0.94rem; }
        .error { color: #b91c1c; margin-top: 14px; font-size: 0.95rem; }
    </style>
</head>
<body>
    <div class="card">
        <h1>Login to PDFGate</h1>
        <form method="POST" action="{{ route('login.attempt') }}">
            @csrf
            <label for="email">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus />

            <label for="password" style="margin-top:16px;">Password</label>
            <input id="password" name="password" type="password" required />

            <button type="submit">Sign in</button>
        </form>

        @if ($errors->any())
            <div class="error">{{ $errors->first() }}</div>
        @endif

        <p class="note">Use the seeded accounts: <strong>admin@example.com / password</strong> or <strong>test@example.com / password</strong>.</p>
    </div>
</body>
</html>
