# Insomnia API Collection for PDFGate

## Setup

1. **Install Insomnia** from https://insomnia.rest/

2. **Import this collection:**
   - Open Insomnia
   - Click "Create" → "Import"
   - Select `Insomnia_API_Collection.json`

3. **Make sure your Laravel server is running:**
   ```bash
   php artisan serve
   ```

## Testing Workflow

### Step 1: Login
1. Open the **"Login"** request (under Authentication folder)
2. Click Send
3. Copy the `token` value from the response
4. In Insomnia, click the Environment dropdown (top-left) and select "Local"
5. Paste the token into the `token` variable

### Step 2: Test Authenticated Endpoints
Now you can use any of these requests:
- **Get Current User** - Fetches your profile
- **Create API Token** - Generate a new API token
- **Logout** - Revoke your current token
- **Revoke Token** - Revoke a specific token by ID

### Test Credentials
```
Email: test@example.com
Password: password
```

## API Endpoints

| Method | Endpoint | Authentication | Description |
|--------|----------|----------------|-------------|
| POST | `/api/login` | ❌ None | Login and get token |
| GET | `/api/user` | ✅ Bearer Token | Get current user info |
| POST | `/api/logout` | ✅ Bearer Token | Logout and revoke token |
| POST | `/api/tokens` | ✅ Bearer Token | Create new API token |
| DELETE | `/api/tokens/{id}` | ✅ Bearer Token | Revoke specific token |

## Environment Variables

The collection uses these variables (found in Local environment):
- `baseUrl` - Base URL (http://localhost:8000)
- `apiUrl` - API base URL (http://localhost:8000/api)
- `token` - Bearer token (set after login)

All authenticated requests automatically use `Authorization: Bearer {{ _.token }}`
