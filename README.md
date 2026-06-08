# PDF Gate

A demo of a paid API service for generating PDFs. This model could be used to generate formatted invoices, manifests
etc using the data of an existing system.

## Demo Features

This demo project includes the following features:

| Feature                   | Description                                                                |
| ------------------------- | -------------------------------------------------------------------------- |
| Auth + API tokens         | Laravel Sanctum, basic token abilities, create/revoke tokens, stateless    |
| Mocked Subsriptions       | Users have a "subscribed" flag. This could be extended into stripe payment |
| PDF Generation            | Generates... er... PDFS                                                    |
| Usage tracking (metered billing) | You can build analytics + billing reconciliation                     |


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

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
