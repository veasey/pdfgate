# PDF Gate

**A demo Laravel API for a paid, metered PDF generation service.**

This project demonstrates how to build a production-ready, asynchronous PDF generation SaaS backend. It can be used to generate invoices, reports, manifests, certificates, or any document by feeding data from an existing system.

Perfect for showcasing Laravel skills in API design, background jobs, PDF handling, authentication, and monetization patterns.

![Laravel](https://img.shields.io/badge/Laravel-13.8-red?style=flat&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.3-blue?style=flat&logo=php)
![Sanctum](https://img.shields.io/badge/Sanctum-Auth-green?style=flat)

## ✨ Key Features

- **🔐 Secure API Authentication** — Laravel Sanctum with personal access tokens (stateless)
- **💳 Mocked Subscription Gating** — Premium features protected by subscription middleware (ready for Stripe)
- **📄 Asynchronous PDF Generation** — Queued jobs using DomPDF + Blade templates
- **📊 Usage Tracking** — Metered billing foundation (tracks PDF count per user)
- **✅ Clean Architecture** — Form Requests, Policies, Jobs, Events-ready structure
- **🧪 Ready for Testing** — PHPUnit tests + full Insomnia collection

## 🚀 Quick Start

### 1. Clone & Setup

```bash
git clone https://github.com/veasey/pdfgate.git
cd pdfgate

# Install dependencies and run setup
composer run setup

# Start development servers (PHP + Queue worker + Vite)
composer run dev
```

### 2. Test Credentials

- *Email:* user@example.com (or test@example.com)
- *Password:* password

### 3. Try the API
Login:
```
Bashcurl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email": "user@example.com", "password": "password"}'
Use the returned token in the Authorization: Bearer <token> header for protected routes.
```
Generate a PDF (Async):
```
Bashcurl -X POST http://localhost:8000/api/pdf \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Invoice #12345",
    "body": "Thank you for your business..."
  }'
```

#### 📋 API Endpoints
*Authentication*

POST /api/login — Login & receive token
GET /api/user — Get current user
POST /api/logout — Revoke current token
POST /api/tokens — Create new token
DELETE /api/tokens/{id} — Revoke token

*PDF Generation*

POST /api/pdf — Submit PDF generation job (returns job ID immediately)
GET /api/pdf/{id} — Check job status + download link when ready

## 🛠️ Tech Stack

- *Framework:* Laravel 13
- *Auth:* Laravel Sanctum
- *PDF:* barryvdh/laravel-dompdf + Blade templates
- *Queue:* Laravel Queue (database driver)
- *Frontend:* Basic Blade + Vite (Tailwind ready)
- *Testing:* PHPUnit + Insomnia collection

```
📁 Project Structure Highlights
textapp/
├── Jobs/GeneratePdfJob.php          # Async PDF processing
├── Models/PdfJob.php                # Tracks generation status & usage
├── Http/Controllers/PdfController.php
├── Http/Middleware/SubscriptionMiddleware.php
database/
├── migrations/                      # Users, PdfJobs, etc.
resources/views/pdf/                 # PDF Blade templates
```


## 🧪 Testing the API

1. Install Insomnia
2. Import Insomnia_API_Collection.json
3. Follow instructions in INSOMNIA_SETUP.md

Or run PHPUnit tests:
Bashcomposer test

## 🎯 What This Demonstrates

- Building scalable async APIs
- Proper job queuing for heavy tasks (PDF generation)
- Authorization & subscription gating
- Usage-based billing foundations
- Clean, maintainable Laravel code

## Future Enhancements (Ideas)

- Real Stripe integration
- S3 storage for generated PDFs
- Multiple templates + dynamic data merging
- Web dashboard for users
- Rate limiting & analytics
- Laravel Horizon + Redis for high volume

## License
This project is open-sourced under the MIT license.

---

_Made with ❤️ by Clint Veasey_