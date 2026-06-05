<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - PDFGate</title>
    <style>
        body { margin: 0; min-height: 100vh; font-family: Inter, system-ui, sans-serif; background: #f8fafc; color: #0f172a; }
        .page { max-width: 1140px; margin: 0 auto; padding: 32px; }
        .header { display:flex; justify-content:space-between; flex-wrap:wrap; gap:18px; align-items:center; margin-bottom:28px; }
        .title { margin:0; font-size:1.8rem; }
        .button { text-decoration:none; display:inline-flex; align-items:center; gap:8px; padding:12px 18px; border-radius:14px; background:#2563eb; color:white; font-weight:700; }
        .panel { background:white; border-radius:24px; box-shadow:0 20px 60px rgba(15,23,42,0.08); padding:28px; }
        table { width:100%; border-collapse:collapse; margin-top:18px; }
        th, td { text-align:left; padding:14px 16px; border-bottom:1px solid #e2e8f0; }
        th { color:#475569; font-size:0.82rem; text-transform:uppercase; letter-spacing:0.05em; }
        .badge { display:inline-flex; align-items:center; gap:6px; padding:6px 10px; border-radius:999px; font-size:0.82rem; font-weight:700; }
        .badge.active { background:#dcfce7; color:#166534; }
        .badge.off { background:#f8fafc; color:#475569; }
        .badge.admin { background:#e0f2fe; color:#0369a1; }
    </style>
</head>
<body>
    <div class="page">
        <div class="header">
            <div>
                <h1 class="title">User Management</h1>
                <p style="margin:8px 0 0; color:#64748b;">All registered users, their subscription state, and PDF usage stats.</p>
            </div>
            <div>
                <a href="{{ route('dashboard') }}" class="button">Back to dashboard</a>
            </div>
        </div>

        <div class="panel">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Subscribed</th>
                        <th>Admin</th>
                        <th>PDFs generated</th>
                        <th>Last generated</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td><span class="badge {{ $user->is_subscribed ? 'active' : 'off' }}">{{ $user->is_subscribed ? 'Yes' : 'No' }}</span></td>
                            <td><span class="badge {{ $user->is_admin ? 'admin' : 'off' }}">{{ $user->is_admin ? 'Admin' : 'User' }}</span></td>
                            <td>{{ $user->pdf_generated_count }}</td>
                            <td>{{ $user->last_generated_at ? $user->last_generated_at->diffForHumans() : 'Never' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
