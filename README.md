# PDF Gate

## Demo Features

This demo project includes the following features:

| Feature                   | Description                                                                |
| ------------------------- | -------------------------------------------------------------------------- |
| Auth + API tokens         | Laravel Sanctum, basic token abilities, create/revoke tokens, stateless    |

## API Authentication

This project uses **Laravel Sanctum** for API token-based authentication. All API endpoints require authentication via bearer tokens.

### Getting Started

1. **Login to get a token:**
   ```bash
   curl -X POST http://localhost:8000/api/login \
     -H "Content-Type: application/json" \
     -d '{"email": "user@example.com", "password": "password"}'
   ```

   Response:
   ```json
   {
     "token": "1|abc123def456...",
     "user": { "id": 1, "name": "User", "email": "user@example.com" }
   }
   ```

2. **Use the token for authenticated requests:**
   ```bash
   curl -X GET http://localhost:8000/api/user \
     -H "Authorization: Bearer 1|abc123def456..."
   ```

### API Endpoints

- `POST /api/login` - Login and receive an API token
- `GET /api/user` - Get authenticated user info
- `POST /api/logout` - Logout and revoke current token
- `POST /api/tokens` - Create a new API token
- `DELETE /api/tokens/{token_id}` - Revoke a specific token

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
