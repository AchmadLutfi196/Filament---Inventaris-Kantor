# Multi-Domain Docker Troubleshooting Guide

## Common Issues and Solutions

### 1. Domain Not Accessible

**Symptoms:**
- Browser shows "This site can't be reached"
- `curl` command fails

**Solutions:**
```bat
# Check hosts file entries
type C:\Windows\System32\drivers\etc\hosts | findstr inventaris

# Add missing entries
PowerShell -ExecutionPolicy Bypass -File add-hosts.ps1

# Restart Docker containers
docker-compose restart
```

### 2. Docker Containers Not Starting

**Symptoms:**
- `docker-compose ps` shows containers as "Exit 1"
- Services not responding

**Solutions:**
```bat
# Check container logs
docker-compose logs

# Rebuild containers
docker-compose down
docker-compose build --no-cache
docker-compose up -d

# Check system resources
docker system df
docker system prune
```

### 3. Database Connection Issues

**Symptoms:**
- Laravel shows database connection errors
- Migration fails

**Solutions:**
```bat
# Wait for MySQL to initialize (can take 30-60 seconds)
timeout /t 60

# Check MySQL logs
docker-compose logs mysql

# Reset database
docker-compose exec app php artisan migrate:fresh --seed
```

### 4. WebSocket/Chat Not Working

**Symptoms:**
- Chat messages not sending
- Real-time updates not working

**Solutions:**
```bat
# Check Reverb container
docker-compose logs reverb

# Restart Reverb service
docker-compose restart reverb

# Verify port is open
netstat -an | findstr ":9090"
```

### 5. SSL Certificate Errors

**Symptoms:**
- Browser shows "Not secure" warning
- SSL handshake failures

**Solutions:**
```bat
# Regenerate SSL certificates
docker-compose exec nginx_user openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout /etc/nginx/ssl/user.key -out /etc/nginx/ssl/user.crt

# Or ignore SSL errors for development
# Access via HTTP instead: http://user.inventaris.local
```

### 6. Permission Denied Errors

**Symptoms:**
- "Access denied" when running scripts
- File permission errors

**Solutions:**
```bat
# Run PowerShell as Administrator
# Enable script execution
Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser

# Fix file permissions in container
docker-compose exec app chmod -R 755 /var/www/storage
docker-compose exec app chmod -R 755 /var/www/bootstrap/cache
```

### 7. Port Already in Use

**Symptoms:**
- Docker complains about port conflicts
- Services fail to bind to ports

**Solutions:**
```bat
# Check what's using the port
netstat -ano | findstr ":80"
netstat -ano | findstr ":8080"

# Stop conflicting services (like XAMPP, WAMP)
# Or change ports in docker-compose.yml
```

### 8. Frontend Assets Not Loading

**Symptoms:**
- CSS/JS files not found
- Styling broken

**Solutions:**
```bat
# Build assets inside container
docker-compose exec app npm install
docker-compose exec app npm run build

# Or build locally and sync
npm install
npm run build
```

## Testing Commands

```bat
# Quick health check
test-multi-domain.bat

# Individual service tests
docker-compose exec app php artisan about
docker-compose exec mysql mysql -u root -p -e "SHOW DATABASES;"
docker-compose exec redis redis-cli ping

# Network connectivity
ping user.inventaris.local
ping admin.inventaris.local
```

## Performance Optimization

```bat
# Clean up Docker system
docker system prune -a

# Optimize containers
docker-compose down
docker-compose up -d --remove-orphans

# Monitor resource usage
docker stats
```

## Getting Help

1. Check container logs: `docker-compose logs [service_name]`
2. Inspect container: `docker-compose exec [service_name] bash`
3. View network info: `docker network ls`
4. Check Docker system: `docker system info`

For more help, check the main documentation: `DOCKER_MULTI_DOMAIN_SETUP.md`
