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

### 2. Configure Environment Variables
Railway will automatically detect Laravel and provide database. You need to add these variables:

#### Required Variables
```
APP_NAME=Warehouse AMTPM System
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app.railway.app
APP_KEY=base64:... (auto-generated or set manually)
APP_LOCALE=id
APP_FALLBACK_LOCALE=en
```

#### Database Configuration
Railway automatically provides `DATABASE_URL`. You can use it directly or configure manually:
```
DB_CONNECTION=mysql
DB_HOST=${DB_HOST}
DB_PORT=${DB_PORT}
DB_DATABASE=${DB_DATABASE}
DB_USERNAME=${DB_USERNAME}
DB_PASSWORD=${DB_PASSWORD}
```

#### Optional Variables
```
# Google OAuth
GOOGLE_CLIENT_ID=your-google-client-id
GOOGLE_CLIENT_SECRET=your-google-client-secret
GOOGLE_REDIRECT_URI=https://your-app.railway.app/auth/google/callback

# Fonnte WhatsApp (if needed)
FONNTE_TOKEN=your-fonnte-token

# AMTPM Configuration
AMTPM_DEFAULT_ROLE=operator

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

### 3. Configure Database Service
Railway will automatically create a PostgreSQL or MySQL database for you. You can:
- Use the default Railway database (recommended)
- Add a MySQL service if you prefer MySQL

To use Railway's database:
1. Go to your project in Railway
2. Click on the database service
3. Copy the connection string from "Variables" tab
4. Set `DATABASE_URL` in your app's environment variables

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
3. Select "New Console"
4. Run: `php artisan db:seed --class=UserSeeder`

#### Option 2: Using Railway CLI
```bash
railway run php artisan db:seed --class=UserSeeder
```

#### Option 3: Using Setup Command
```bash
railway run php artisan setup:admin
```

Then visit your app and login with the seeded admin credentials.

### 6. Storage Configuration
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

### 7. Monitoring
- Check logs in Railway Dashboard → "Logs" tab
- Monitor metrics in "Metrics" tab
- Set up alerts in "Alerts" tab

## Troubleshooting

### Build Fails
- Check logs in Railway Dashboard
- Ensure `composer.json` and `package.json` are valid
- Verify all dependencies are compatible

### Database Connection Error
- Verify `DATABASE_URL` is set correctly
- Check database service is running
- Ensure database migrations ran successfully

### Permission Errors
- Railway uses read-only filesystem for most directories
- Use cloud storage for file uploads
- Ensure `storage:link` command runs successfully

### App Not Loading
- Check if port is correctly configured (Railway uses `$PORT` variable)
- Verify `APP_URL` matches your Railway domain
- Check healthcheck status in Railway Dashboard

## Custom Domain (Optional)
1. Go to "Settings" → "Networking"
2. Click "Add Custom Domain"
3. Enter your domain (e.g., `warehouse.yourdomain.com`)
4. Configure DNS records as instructed
5. Update `APP_URL` environment variable

## Notes
- Railway automatically assigns a domain like `https://your-app-name.up.railway.app`
- Free tier has limitations (512MB RAM, 500 hours/month)
- For production, consider upgrading to paid plans
- Always set `APP_DEBUG=false` in production
- Use environment-specific configuration for production

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
