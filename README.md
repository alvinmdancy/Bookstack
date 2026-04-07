Portable BookStack Distribution

A fully containerized BookStack instance with database snapshots and image assets tracked in Git. Clone once, run anywhere.

## Features

- **One-command installation** - Automated setup script
- **Docker-based** - No local PHP/MySQL installation needed
- **Database snapshots** - Pre-configured with sample data
- **Git-tracked images** - All uploaded images version-controlled
- **Portable** - Clone to any machine and run immediately
- **Isolated networking** - Containers communicate on private network
- **Automated backups** - Keep latest 3 backups with one command

---

## Prerequisites

### Windows
- [Docker Desktop](https://www.docker.com/products/docker-desktop/) (with WSL2 enabled)
- [Git for Windows](https://git-scm.com/download/win)
- Windows 10/11

### Linux/Mac
- Docker & Docker Compose
- Git

---

## Quick Start

### Windows

1. **Clone the repository**
```cmd
   git clone https://github.com/alvinmdancy/bookstack-git.git
   cd bookstack-git
```

2. **Run the installer**
```cmd
   install.bat
```

3. **Access BookStack**
   
   Open your browser: **http://localhost:8085**
   
   Default credentials:
   - **Email:** `admin@admin.com`
   - **Password:** `password`

### Linux/Mac

1. **Clone the repository**
```bash
   git clone https://github.com/alvinmdancy/bookstack-git.git
   cd bookstack-git
```

2. **Make installer executable and run**
```bash
   chmod +x install.sh
   ./install.sh
```
   
   Or manually:
```bash
   docker compose up -d
   docker exec -i mariadb mysql -uroot -psecretsrootpass bookstackapp < db/bookstack.sql
```

3. **Access BookStack**
   
   Open your browser: **http://localhost:8085**

---

## Project Structure
bookstack-git/
├── backup/
│   ├── backup.sh           # Linux backup script
│   ├── backup.bat          # Windows backup script
│   └── README.md           # Backup documentation
├── restore/
│   ├── restore.sh          # Linux restore script
│   ├── restore.bat         # Windows restore script
│   └── README.md           # Restore documentation
├── backups/                # Generated backups (gitignored)
├── docker-compose.yml      # Container orchestration
├── install.bat             # Windows installer
├── db/
│   └── bookstack.sql       # Database snapshot
├── storage/
│   └── images/             # Git-tracked uploaded images
└── README.md

---

## Common Commands

### Start containers
```bash
docker compose up -d
```

### Stop containers
```bash
docker compose down
```

### View logs
```bash
docker compose logs -f bookstack
docker compose logs -f mariadb
```

### Restart BookStack
```bash
docker compose restart bookstack
```

### Access MariaDB shell
```bash
docker exec -it mariadb mysql -u bookstack -pbookstackpass bookstackapp
```

### Backup database manually
```bash
docker exec mariadb mysqldump -uroot -psecretsrootpass bookstackapp > db/bookstack_backup_$(date +%Y%m%d).sql
```

### Restore database manually
```bash
docker exec -i mariadb mysql -uroot -psecretsrootpass bookstackapp < db/bookstack.sql
```

---

## Backup and Restore

### Create a backup

**Linux/Mac:**
```bash
cd backup
./backup.sh
```

**Windows:**
```cmd
cd backup
backup.bat
```

Features:
- Backs up database and images
- Compresses to .tar.gz format
- Automatically keeps only the 3 most recent backups
- Backups saved to `backups/` directory

### Restore from backup

**Linux/Mac:**
```bash
cd restore
./restore.sh
```

**Windows:**
```cmd
cd restore
restore.bat
```

Features:
- Lists all available backups
- Interactive selection (1 = most recent)
- Confirms before overwriting data
- Restores database and images

See `backup/README.md` and `restore/README.md` for detailed documentation.

---

## Troubleshooting

### Images not showing
```bash
# Check if images directory is mounted correctly
docker exec -it bookstack ls -la /config/www/uploads/images

# Fix permissions if needed
docker exec -it bookstack chown -R abc:abc /config/www/uploads/images
```

### Database connection errors
```bash
# Check if MariaDB is healthy
docker compose ps

# View MariaDB logs
docker compose logs mariadb

# Restart MariaDB
docker compose restart mariadb
```

### Port 8085 already in use
Edit `docker-compose.yml` and change the port mapping:
```yaml
ports:
  - "8086:80"  # Change 8085 to any available port
```

### Installer fails at database restore
1. Check if `db/bookstack.sql` exists
2. Verify database credentials match in `docker-compose.yml` and `install.bat`
3. Ensure MariaDB is fully started (wait 10-15 seconds)

---

## Security Notes

**WARNING: For production use:**

1. **Change default passwords** in `docker-compose.yml`:
```yaml
   MARIADB_ROOT_PASSWORD: <strong-password>
   MARIADB_PASSWORD: <strong-password>
```

2. **Generate new APP_KEY**:
```bash
   docker exec -it bookstack php artisan key:generate --show
```
   Then update `APP_KEY` in `docker-compose.yml`

3. **Change BookStack admin password** after first login

4. **Use HTTPS** with a reverse proxy (nginx, Caddy, Traefik)

5. **Don't commit sensitive data** to public repos

---

## Updating BookStack
```bash
# Pull latest BookStack image
docker compose pull bookstack

# Recreate containers
docker compose up -d --force-recreate

# Run migrations
docker exec -it bookstack php artisan migrate --force
```

---

## Cloning to New Machine

1. **Clone the repository**
```bash
   git clone https://github.com/alvinmdancy/bookstack-git.git
   cd bookstack-git
```

2. **Run installer** (Windows: `install.bat`, Linux/Mac: `install.sh`)

3. All images and database content will be automatically restored.

---

## Environment Variables

Key configuration in `docker-compose.yml`:

| Variable | Default | Description |
|----------|---------|-------------|
| `APP_URL` | `http://localhost:8085` | BookStack URL |
| `DB_HOST` | `mariadb` | Database hostname |
| `DB_DATABASE` | `bookstackapp` | Database name |
| `DB_USERNAME` | `bookstack` | Database user |
| `DB_PASSWORD` | `bookstackpass` | Database password |
| `MARIADB_ROOT_PASSWORD` | `secretrootpass` | MariaDB root password |
| `TZ` | `America/New_York` | Container timezone |

---

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Submit a pull request

---

## License

This project is for educational and personal use. BookStack itself is licensed under MIT.

---

## Support

- **BookStack Documentation**: https://www.bookstackapp.com/docs/
- **Issues**: Open an issue on GitHub
- **Docker Help**: https://docs.docker.com/

---

## Use Cases

- Personal knowledge base
- Team documentation
- Internal wiki
- Project documentation
- Student notes
- Client documentation portal

---

**Made for portable documentation**
