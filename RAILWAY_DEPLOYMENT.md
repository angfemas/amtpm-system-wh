# Railway Deployment Guide

## Prerequisites
- Railway account (https://railway.app)
- GitHub repository connected to Railway
- Railway CLI (optional)

## Deployment Steps

### 1. Connect Repository to Railway
1. Login to Railway (https://railway.app)
2. Click "New Project" → "Deploy from GitHub repo"
3. Select `angfemas/amtpm-system-wh` repository
4. Choose branch (default: `main`)
5. Click "Deploy Now"

### 2. Add Database Service
1. In Railway dashboard, click "New Service"
2. Select "Database"
3. Choose "MySQL" or "PostgreSQL" (MySQL recommended)
4. Click "Add Database"
5. Railway will automatically link database to your app

### 3. Configure Environment Variables
Railway will automatically detect Laravel and provide database. You need to add these variables:

#### Required Variables
```
APP_NAME=Warehouse AMTPM System
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app.railway.app
APP_KEY=base64:... (generate locally with: php artisan key:generate --show)
APP_LOCALE=id
APP_FALLBACK_LOCALE=en
ASSET_URL=https://your-app.railway.app
AMTPM_DEFAULT_ROLE=operator
```

#### Database Configuration
Railway MySQL service provides database credentials. You need to copy these and add them as standard Laravel `DB_*` variables to your web service.

**Steps:**
1. Add a MySQL Database service in Railway
2. Go to the MySQL service → tab "Variables"
3. Copy these values:
   - `MYSQLHOST` → will be `DB_HOST`
   - `MYSQLPORT` → will be `DB_PORT`
   - `MYSQLDATABASE` → will be `DB_DATABASE`
   - `MYSQLUSER` → will be `DB_USERNAME`
   - `MYSQLPASSWORD` → will be `DB_PASSWORD`
4. Go to your web service → tab "Variables"
5. Add these Laravel standard variables:
   ```
   DB_CONNECTION=mysql
   DB_HOST=mysql.railway.internal
   DB_PORT=3306
   DB_DATABASE=railway
   DB_USERNAME=root
   DB_PASSWORD=your_password_here
   ```
6. Replace values with actual values from MySQL service
7. The AppServiceProvider will automatically use these to configure the database connection

#### Optional Variables
```
# Google OAuth
GOOGLE_CLIENT_ID=your-google-client-id
GOOGLE_CLIENT_SECRET=your-google-client-secret
GOOGLE_REDIRECT_URI=https://your-app.railway.app/auth/google/callback

# Fonnte WhatsApp (if needed)
FONNTE_TOKEN=your-fonnte-token

# Scanner Configuration
SCANNER_SCAN_WIDTH=320
SCANNER_SCAN_HEIGHT=240
SCANNER_SCAN_INTERVAL=120
SCANNER_CAMERA_WIDTH=640
SCANNER_CAMERA_HEIGHT=480
SCANNER_FACING_MODE=environment
SCANNER_WORKER_SCRIPT=/js/qr-scanner-worker.js

# Image Compression
IMAGE_MAX_WIDTH=1920
IMAGE_MAX_HEIGHT=1080
IMAGE_QUALITY=80
IMAGE_CONVERT_TO_WEBP=false
IMAGE_PROGRESSIVE_JPEG=true
IMAGE_OPTIMIZE_PNG=true
```

### 4. Build Configuration
The `railway.json` file is already configured with:
- Builder: NIXPACKS
- Build command: `composer install --no-dev --optimize-autoloader && npm install && npm run build`
- Start command: Runs migrations, storage link, caching, and starts server

### 5. First-Time Setup
After deployment, you need to create an admin user. You can:

#### Option 1: Using Railway Console
1. Go to your app in Railway
2. Click "Console" tab
3. Click "New Console"
4. Run: `php artisan db:seed`

#### Option 2: Using Railway CLI
```bash
railway run php artisan db:seed
```

#### Option 3: Using Setup Command
```bash
railway run php artisan setup:admin
```

Then visit your app and login with the seeded admin credentials.

### 6. Default Login Credentials
After running seeder, you can login with:

**Admin:**
- NRP: `ADM001`
- Email: `admin@warehouse-amtpm.com`
- Password: `Adm!n@Warehouse2024`

**Leader:**
- NRP: `LDR001`
- Email: `leader@warehouse-amtpm.com`
- Password: `L3ad3r@Team2024`

**Operator:**
- NRP: `OPR001`
- Email: `operator@warehouse-amtpm.com`
- Password: `Op3rat0r@Work2024`

### 7. Storage Configuration
For file uploads to work properly, you may want to use a cloud storage service:
- AWS S3 (recommended for production)
- Cloudflare R2
- Backblaze B2

Configure AWS S3 in environment variables:
```
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=your-access-key
AWS_SECRET_ACCESS_KEY=your-secret-key
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=your-bucket-name
AWS_URL=https://your-bucket.s3.amazonaws.com
```

### 8. Post-Deployment Commands
After successful deployment, run these in Railway Console:

```bash
# Create storage link
php artisan storage:link

# Clear caches
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
```

### 9. Monitoring
- Check logs in Railway Dashboard → "Logs" tab
- Monitor metrics in "Metrics" tab
- Set up alerts in "Alerts" tab

## Troubleshooting

### Build Fails
- Check logs in Railway Dashboard
- Ensure `composer.json` and `package.json` are valid
- Verify all dependencies are compatible

### Database Connection Error
- Verify `DATABASE_URL` is set correctly by Railway
- Check database service is running
- Ensure database migrations ran successfully

### Missing App Key Error
Error: "No application encryption key has been specified"

**Solution:**
1. Generate locally: `php artisan key:generate --show`
2. Copy the output (format: `base64:...`)
3. Set `APP_KEY` in Railway Variables tab
4. Railway will redeploy automatically

### Duplicate Column Error in activity_log
If you encounter duplicate column error for `activity_log` table:

**Solution 1: Rollback Migration**
```bash
php artisan migrate:rollback --step=1
php artisan migrate --force
```

**Solution 2: Mark Migration as Completed**
```bash
php artisan tinker
>>> DB::table('migrations')->insert(['migration' => '2026_10_26_155389_add_event_column_to_activity_log_table', 'batch' => 1]);
>>> exit
php artisan migrate --force
```

### Role Not Found Error
Error: "There is no role named 'admin' for guard `web`"

**Solution:**
Run RolePermissionSeeder before UserSeeder:
```bash
php artisan db:seed --class=RolePermissionSeeder
php artisan db:seed --class=UserSeeder
```

Or run all seeders:
```bash
php artisan db:seed
```

### Permission Errors
- Railway uses read-only filesystem for most directories
- Use cloud storage for file uploads
- Ensure `storage:link` command runs successfully

### App Not Loading
- Check if port is correctly configured (Railway uses `$PORT` variable)
- Verify `APP_URL` matches your Railway domain
- Check healthcheck status in Railway Dashboard

### "Not Secure" Form Warning
Error: "The information you're about to submit is not secure"

**Solution:**
- Ensure `APP_URL` uses HTTPS
- Set `ASSET_URL` to the same HTTPS URL
- Railway automatically provides SSL/HTTPS
- The app now trusts Railway's proxy headers

### Assets Not Loading (Dashboard Looks Broken)
**Solution:**
```bash
# Run these in Railway Console
php artisan storage:link
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
```

Check if build succeeded by looking at Railway Logs for Vite build output.

## Custom Domain (Optional)
1. Go to "Settings" → "Networking"
2. Click "Add Custom Domain"
3. Enter your domain (e.g., `warehouse.yourdomain.com`)
4. Configure DNS records as instructed
5. Update `APP_URL` and `ASSET_URL` environment variables

## Notes
- Railway automatically assigns a domain like `https://your-app-name.up.railway.app`
- Free tier has limitations (512MB RAM, 500 hours/month)
- For production, consider upgrading to paid plans
- Always set `APP_DEBUG=false` in production
- Use environment-specific configuration for production
- Do NOT use `npm run dev` in production - use `npm run build` (already configured in railway.json)

## Railway CLI (Optional)
Install Railway CLI for local development:
```bash
npm install -g @railway/cli
railway login
railway init
railway up
```

Useful commands:
```bash
railway logs              # View logs
railway variables         # Manage environment variables
railway open              # Open app in browser
railway run <command>     # Run command in app context
```
