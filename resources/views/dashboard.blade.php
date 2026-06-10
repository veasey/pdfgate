<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - PDFGate</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { margin: 0; min-height: 100vh; font-family: Inter, system-ui, sans-serif; background: #f8fafc; color: #0f172a; }
        .page { max-width: 1140px; margin: 0 auto; padding: 32px; }
        .bar { display: flex; justify-content: space-between; flex-wrap: wrap; gap: 20px; }
        .card { background: white; border-radius: 24px; box-shadow: 0 20px 60px rgba(15, 23, 42, 0.08); padding: 28px; flex: 1 1 240px; min-width: 240px; }
        .card h2 { margin: 0 0 10px; font-size: 1rem; color: #475569; letter-spacing: 0.01em; }
        .value { font-size: 2rem; font-weight: 800; margin: 0; color: #111827; }
        .top-bar { display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:16px; margin-bottom:24px; }
        .title { margin:0; font-size:1.8rem; }
        .button { text-decoration:none; display:inline-flex; align-items:center; gap:8px; padding:12px 18px; border-radius:14px; background:#2563eb; color:white; font-weight:700; }
        .section { margin-top:32px; }
        .panel { background:white; border-radius:24px; box-shadow:0 20px 60px rgba(15,23,42,0.08); padding:28px; }
        table { width:100%; border-collapse:collapse; margin-top:16px; }
        th, td { text-align:left; padding:12px 14px; border-bottom:1px solid #e2e8f0; }
        th { color:#475569; font-size:0.92rem; text-transform:uppercase; letter-spacing:0.05em; }
        .badge { display:inline-flex; align-items:center; gap:6px; padding:6px 10px; border-radius:999px; font-size:0.82rem; font-weight:700; }
        .badge.green { background:#dcfce7; color:#166534; }
        .badge.blue { background:#e0f2fe; color:#0369a1; }
        .badge.gray { background:#f3f4f6; color:#475569; }
    </style>
</head>
<body>
    <div class="page">
        <div class="top-bar">
            <div>
                <h1 class="title">Dashboard</h1>
                <p style="color:#64748b; margin:8px 0 0;">Welcome, {{ auth()->user()->name }}. Track PDF usage and subscriptions.</p>
            </div>
            <div>
                <a href="{{ route('pdf.builder') }}" class="button">Generate PDF</a>
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="button" style="background:#0f172a;">Sign out</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
            </div>
        </div>

        <div class="bar">
            <div class="card">
                <h2>Total users</h2>
                <p class="value">{{ number_format($totalUsers) }}</p>
            </div>
            <div class="card">
                <h2>Subscribed users</h2>
                <p class="value">{{ number_format($subscribedUsers) }}</p>
            </div>
            <div class="card">
                <h2>PDFs generated</h2>
                <p class="value">{{ number_format($totalPdfCount) }}</p>
            </div>
        </div>

        <div class="section panel">
            <h2>API tokens</h2>
            <p style="color:#64748b; margin:8px 0 16px;">Manage your personal access tokens for API requests.</p>

            @if(auth()->user()->apiTokens->isEmpty())
                <p style="color:#475569; margin:0;">No API tokens have been issued yet.</p>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Last used</th>
                            <th>Abilities</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(auth()->user()->apiTokens as $token)
                            <tr>
                                <td>{{ $token->name }}</td>
                                <td>{{ optional($token->last_used_at)->diffForHumans() ?? 'Never' }}</td>
                                <td>{{ implode(', ', $token->abilities) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <div class="section panel">
            <h2>Your PDFs</h2>
            <p style="color:#64748b; margin:8px 0 16px;">Recent PDFs you've generated. Click to view or download when ready.</p>

            @if(isset($pdfJobs) && $pdfJobs->isEmpty())
                <p style="color:#475569; margin:0;">You haven't generated any PDFs yet.</p>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pdfJobs ?? [] as $job)
                            <tr>
                                <td>{{ $job->id }}</td>
                                <td>{{ $job->payload['title'] ?? '-' }}</td>
                                <td>
                                    @if($job->status === \App\Models\PdfJob::STATUS_PENDING)
                                        <span class="badge gray">Pending</span>
                                    @elseif($job->status === \App\Models\PdfJob::STATUS_PROCESSING)
                                        <span class="badge blue">Processing</span>
                                    @elseif($job->status === \App\Models\PdfJob::STATUS_COMPLETED)
                                        <span class="badge green">Completed</span>
                                    @else
                                        <span class="badge gray">{{ ucfirst($job->status) }}</span>
                                    @endif
                                </td>
                                <td>{{ $job->created_at->diffForHumans() }}</td>
                                <td>
                                    <a href="{{ route('pdf.show', $job) }}" class="button" style="background:#0f172a; padding:8px 10px;">View</a>
                                    @if($job->status === \App\Models\PdfJob::STATUS_COMPLETED && $job->result)
                                        <a href="{{ route('pdf.download', $job) }}" class="button" style="background:#10b981; padding:8px 10px; margin-left:8px;">Download</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <div class="section panel">
            <h2>Top PDF users</h2>
            <canvas id="usageChart" style="max-height:420px;"></canvas>
        </div>

        @if(auth()->user()->is_admin)
            <div class="section panel">
                <div style="display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap;">
                    <div>
                        <h2>Admin user management</h2>
                        <p style="color:#64748b; margin:8px 0 0;">Manage subscriptions, usage, and accounts.</p>
                    </div>
                    <a href="{{ route('admin.users') }}" class="button">Manage users</a>
                </div>
            </div>
        @endif
    </div>

    <script>
        const ctx = document.getElementById('usageChart');
        const usageChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($topUserLabels),
                datasets: [{
                    label: 'PDFs generated',
                    data: @json($topUserCounts),
                    backgroundColor: '#2563eb',
                    borderRadius: 12,
                    borderSkipped: false,
                }],
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: { callbacks: { label: ctx => `${ctx.dataset.label}: ${ctx.formattedValue}` } }
                },
                scales: {
                    x: { grid: { display: false } },
                    y: { beginAtZero: true, ticks: { precision: 0 } }
                }
            }
        });
    </script>
</body>
</html>
