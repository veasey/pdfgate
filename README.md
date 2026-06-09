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