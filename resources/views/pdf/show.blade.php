<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF Job #{{ $job->id }} - PDFGate</title>
    <style>body{font-family:Inter, system-ui, sans-serif; padding:24px; background:#f8fafc; color:#0f172a;} .card{background:white; padding:20px; border-radius:12px; box-shadow:0 10px 30px rgba(15,23,42,0.06); max-width:900px; margin:0 auto;} .button{display:inline-block;padding:10px 14px;background:#2563eb;color:#fff;border-radius:10px;text-decoration:none;font-weight:700;margin-right:8px;} .muted{color:#6b7280}</style>
</head>
<body>
    <div class="card">
        <h1>PDF Job #{{ $job->id }}</h1>
        <p class="muted">Created: {{ $job->created_at->toDayDateTimeString() }}</p>

        <h3 style="margin-top:16px;">Title</h3>
        <p>{{ $job->payload['title'] ?? '-' }}</p>

        <h3 style="margin-top:8px;">Body</h3>
        <p>{{ $job->payload['body'] ?? '-' }}</p>

        <h3 style="margin-top:8px;">Status</h3>
        <p>{{ ucfirst($job->status) }}</p>

        <div style="margin-top:20px;">
            @if($job->status === \App\Models\PdfJob::STATUS_COMPLETED && $job->result)
                <a href="{{ route('pdf.download', $job) }}" class="button">Download PDF</a>
                <a href="data:application/pdf;base64,{{ $job->result }}" download="document.pdf" class="button" style="background:#10b981;">Download (inline)</a>
            @else
                <p class="muted">PDF is not ready yet. Refresh the page later.</p>
            @endif
            <a href="{{ route('dashboard') }}" class="button" style="background:#0f172a;">Back</a>
        </div>
    </div>
</body>
</html>
