# AMTPM Warehouse System - Project Information

## Project Overview
This is a Laravel-based warehouse management system (AMTPM System) built with:
- Laravel 12.58.0
- Spatie Permission for role-based access control
- Socialite for Google OAuth authentication
- Blade templates for frontend

## Authentication System
The project uses a dual authentication system:
1. **Traditional Authentication**: NRP (Nomor Register Personil) or Email + Password
2. **Google OAuth**: Sign in with Google

### Auth Controllers
- `AuthenticatedSessionController` - Login/Logout
- `RegisteredUserController` - Registration
- `PasswordResetLinkController` - Forgot password (uses NRP)
- `NewPasswordController` - Reset password
- `ConfirmablePasswordController` - Password confirmation (uses NRP)
- `GoogleAuthController` - Google OAuth

### Key Authentication Features
- NRP-based login (custom identifier)
- Email is optional
- Default role: 'operator' (configurable via env `AMTPM_DEFAULT_ROLE`)
- Email verification warning if not verified

## Configuration Files

### New Config File: `config/amtpm.php`
- `default_role`: Default role for new users (default: 'operator')
- `allowed_roles`: List of allowed roles (admin, leader, operator)

### New Config File: `config/sections.php`
- `sections`: List of available warehouse sections (PKWH1-PKWH6, etc)
- `default_section`: Default section for new users (configurable via env)

### Language Files
Created `lang/id/` directory with:
- `auth.php` - Authentication messages
- `messages.php` - General UI messages
- `warehouse.php` - Warehouse-related messages

## Best Practices Implemented

### 1. No Hardcoded Values
- Role assignment moved to config file
- All messages moved to language files
- Default role configurable via `.env` with `AMTPM_DEFAULT_ROLE`

### 2. Consistent Redirects
- All redirects use `route('dashboard', absolute: false)` for consistency
- Avoids hardcoded URLs

### 3. Security
- Uses `Hash::make()` instead of deprecated `bcrypt()`
- NRP-based authentication for internal users
- Email verification check with warning

### 4. Database Consistency
- Password reset uses NRP to find user, then sends email to user's email
- Confirm password uses NRP instead of email

## Verification Commands

```bash
# Clear config cache after changes
php artisan config:clear

# Clear route cache
php artisan route:clear

# View all routes
php artisan route:list

# Run tests (if available)
php artisan test
```

## Environment Variables (Recommended)
Add to `.env`:
```
AMTPM_DEFAULT_ROLE=operator
APP_LOCALE=id
APP_FALLBACK_LOCALE=en
```

## Notes
- The project uses NRP as the primary identifier (see `User::getAuthIdentifierName()`)
- Email is optional for users (nullable in database)
- Google OAuth creates users with random passwords
- All user-facing messages are in Indonesian (id locale)

## Profile System

### User Fields
- `section`: Warehouse section assignment (optional, from config/sections.php)
- `status`: Account status (active/inactive/suspended, default: active)
- `last_login_at`: Timestamp of last login (tracked automatically)
- `notification_enabled`: Notification preference (boolean, default: true)
- `profile_photo_path`: Path to uploaded profile photo
- `google_avatar`: URL to Google OAuth avatar

### Profile Features
- Upload/remove profile photo
- Select section from dropdown (configurable by admin)
- Toggle notifications on/off
- View account info (type, status, member since, last login)
- Change password
- Admin can edit user sections and status

### Configuration
Sections are managed in `config/sections.php`:
- Default sections: PKWH1-PKWH6
- Can be extended by admin for production

## Scanner System

### QR Scanner Features
- Real-time camera-based QR code scanning
- Manual input fallback
- Web Worker for performance (non-blocking UI)
- Responsive design for mobile and desktop
- Auto-redirect to unit detail or maintenance checklist

### Scanner Configuration
Configuration is managed in `config/scanner.php`:
- `scan_width`: Scan resolution width (default: 320)
- `scan_height`: Scan resolution height (default: 240)
- `scan_interval`: Scan interval in milliseconds (default: 120)
- `camera_width`: Camera resolution width (default: 640)
- `camera_height`: Camera resolution height (default: 480)
- `facing_mode`: Camera facing mode (default: 'environment')
- `worker_script`: Path to worker script (default: '/js/qr-scanner-worker.js')

### Environment Variables (Recommended)
Add to `.env`:
```
SCANNER_SCAN_WIDTH=320
SCANNER_SCAN_HEIGHT=240
SCANNER_SCAN_INTERVAL=120
SCANNER_CAMERA_WIDTH=640
SCANNER_CAMERA_HEIGHT=480
SCANNER_FACING_MODE=environment
SCANNER_WORKER_SCRIPT=/js/qr-scanner-worker.js
```

### Language Files
Created `lang/id/` directory with:
- `scanner.php` - Scanner UI messages
- `qrcodes.php` - QR codes management messages

### Routes
- `GET /qr-codes` - QR codes management page (index)
- `GET /scan` - QR scanner page
- `GET /qr-codes/generate/{unit}` - Generate QR code for unit
- `GET /qr-codes/download/{unit}` - Download QR code
- `GET /qr-codes/{unit}` - Show QR code preview

### Sidebar Navigation
- Tools section contains:
  - "QR Codes" - Manage and view all QR codes
  - "Scanner" - Access QR scanner for scanning units

## Railway Deployment

### Deployment Files
- `railway.json` - Railway build and deploy configuration
- `Procfile` - Heroku-style process file for web server
- `.railway/start.sh` - Startup script for migrations and caching
- `RAILWAY_DEPLOYMENT.md` - Complete deployment guide

### Railway Configuration
The project is configured for Railway deployment with:
- Builder: NIXPACKS
- Build command: Composer install + npm install + npm run build
- Start command: Generate key, run migrations, storage link, cache configs/routes/views, start server
- Healthcheck: Root path with 100s timeout

### Environment Variables for Railway
Required variables for production:
```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app.railway.app
APP_KEY=base64:...
APP_LOCALE=id
APP_FALLBACK_LOCALE=en
AMTPM_DEFAULT_ROLE=operator
DATABASE_URL=... (provided by Railway)
```

### Deployment Steps
1. Connect GitHub repository to Railway
2. Configure environment variables
3. Railway automatically builds and deploys
4. Run database seeder to create admin user: `php artisan db:seed --class=UserSeeder`
5. Or use setup command: `php artisan setup:admin`

### See Also
- Full deployment guide: `RAILWAY_DEPLOYMENT.md`
- Railway CLI documentation: https://docs.railway.app
