# Multi-Domain Inventaris System with Docker

## Setup Instructions

### Prerequisites
- Docker Desktop installed and running
- PowerShell with Administrator privileges

### Step 1: Setup Docker Environment

1. **Run the setup script:**
   ```bat
   setup-docker.bat
   ```

2. **Add domains to hosts file (Administrator required):**
   ```powershell
   # Run PowerShell as Administrator
   Add-Content -Path C:\Windows\System32\drivers\etc\hosts -Value "127.0.0.1 user.inventaris.local"
   Add-Content -Path C:\Windows\System32\drivers\etc\hosts -Value "127.0.0.1 admin.inventaris.local"
   ```

### Step 2: Access the Applications

- **User Frontend:** http://user.inventaris.local
- **Admin Panel:** http://admin.inventaris.local:8080

### Step 3: Domain Separation Features

#### User Domain (user.inventaris.local)
- Only frontend routes are accessible
- Registration for new users
- Dashboard, inventory browsing, loan requests
- Chat with admin
- Blocked from accessing `/admin` or `/filament` routes

#### Admin Domain (admin.inventaris.local:8080)
- Only admin panel routes are accessible
- Filament admin interface
- Inventory management
- User management
- Chat with users
- Blocked from accessing `/frontend` routes
- Root URL redirects to `/admin`

### Step 4: SSL Configuration (Optional)

The setup includes self-signed SSL certificates for HTTPS:
- **User HTTPS:** https://user.inventaris.local
- **Admin HTTPS:** https://admin.inventaris.local:8443

### Docker Services

1. **MySQL Database** - Port 3306
2. **Redis Cache** - Port 6379
3. **User Frontend** - Port 80/443
4. **Admin Panel** - Port 8080/8443
5. **Queue Worker** - Background processing
6. **Laravel Reverb** - WebSocket for real-time chat

### Development Commands

```bat
# Start all services
docker-compose up -d

# Stop all services
docker-compose down

# View logs
docker-compose logs -f

# Access application container
docker-compose exec app bash

# Run artisan commands
docker-compose exec app php artisan migrate
docker-compose exec app php artisan queue:work

# Rebuild containers
docker-compose build --no-cache
docker-compose up -d --force-recreate
```

### Environment Variables

Key environment variables in `.env.docker`:

```env
USER_DOMAIN=user.inventaris.local
ADMIN_DOMAIN=admin.inventaris.local
APP_URL=http://user.inventaris.local
ADMIN_URL=http://admin.inventaris.local:8080
DB_HOST=mysql
REDIS_HOST=redis
REVERB_HOST=localhost
```

### Troubleshooting

1. **Cannot access domains:**
   - Ensure hosts file entries are added
   - Check if Docker containers are running: `docker-compose ps`

2. **Database connection issues:**
   - Wait for MySQL to fully start (30-60 seconds)
   - Check MySQL logs: `docker-compose logs mysql`

3. **Permission errors:**
   - Run setup script as Administrator
   - Check Docker Desktop permissions

4. **Chat not working:**
   - Ensure Reverb container is running
   - Check WebSocket connection on port 8080

### Security Notes

- Self-signed certificates will show browser warnings
- For production, use proper SSL certificates
- Change default database passwords
- Configure proper firewall rules

### Multi-Domain Architecture

```
┌─────────────────────┐    ┌─────────────────────┐
│   User Domain       │    │   Admin Domain      │
│  user.inventaris    │    │  admin.inventaris   │
│      :80/:443       │    │    :8080/:8443      │
└─────────┬───────────┘    └─────────┬───────────┘
          │                          │
          └─────────┬──────────────────┘
                    │
          ┌─────────▼───────────┐
          │    Laravel App      │
          │    (PHP-FPM)        │
          └─────────┬───────────┘
                    │
    ┌───────────────┼───────────────┐
    │               │               │
┌───▼───┐    ┌─────▼─────┐    ┌───▼────┐
│ MySQL │    │   Redis   │    │ Reverb │
│  :3306│    │   :6379   │    │ :8080  │
└───────┘    └───────────┘    └────────┘
```
