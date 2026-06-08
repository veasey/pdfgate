<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDFGate - Generate PDFs via API</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])s
</head>
<body>
    <div class="container">
        <h1>📄 PDFGate</h1>
        <p class="tagline">Generate PDFs via API</p>
        <p class="description">
            Create high-quality PDFs programmatically. Simple, fast, and built for developers.
        </p>

        <div class="features">
            <div class="feature">
                <h3>⚡ Fast API</h3>
                <p>Generate PDFs instantly via RESTful endpoints</p>
            </div>
            <div class="feature">
                <h3>🔒 Secure</h3>
                <p>Token-based authentication & subscription support</p>
            </div>
            <div class="feature">
                <h3>📊 Track Usage</h3>
                <p>Monitor your API tokens and PDF generation stats</p>
            </div>
            <div class="feature">
                <h3>💳 Subscription</h3>
                <p>Flexible billing plans for your needs</p>
            </div>
        </div>

        <a href="{{ route('login') }}" class="cta">Get Started</a>

        <div class="footer">
            Already have an account? <a href="{{ route('login') }}">Log in here</a>
        </div>
    </div>
</body>
</html>
