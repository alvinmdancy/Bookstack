# Portable BookStack Distribution

A fully containerized and portable BookStack deployment with automated installation, database snapshots, and optional versioned assets. Designed to be clone-and-run anywhere with minimal setup.

---

## Features

- One-command installation (Windows + Linux support)
- Fully Docker-based (no PHP/MySQL installation required)
- Preloaded database snapshot support
- Image asset persistence (Git-tracked optional assets)
- Portable deployment (clone → install → run)
- Isolated Docker networking between services
- Automated backup system (keeps latest 3 backups)
- Easy restore system with interactive selection
- Version-controlled deployment system (VERSION file + Git tags)

---

## Prerequisites

### Windows

- Docker Desktop (WSL2 enabled)
- Git for Windows
- Windows 10/11

### Linux / macOS

- Docker + Docker Compose
- Git

---

## Quick Start

### Windows

```bash
git clone https://github.com/alvinmdancy/Bookstack.git
cd Bookstack
install.bat
```

Then open: `http://localhost:8085`

**Default Login**

| Field    | Value           |
|----------|-----------------|
| Email    | admin@admin.com |
| Password | password        |

### Linux / macOS

```bash
git clone https://github.com/alvinmdancy/Bookstack.git
cd Bookstack
chmod +x install.sh
./install.sh
```

Or manual start:

```bash
docker compose up -d
docker exec -i mariadb mysql -uroot -psecretsrootpass bookstackapp < db/bookstack.sql
```

---

## Project Structure

```
Bookstack/
└── bookstack-git/
    ├── assets/
    │   └── bookstack.ico
    ├── backup/
    │   ├── backup.bat
    │   ├── backup.sh
    │   └── README.md
    ├── backups/                    # Runtime backups (gitignored)
    │   └── YYYYMMDD_HHMMSS.tar.gz
    ├── bookstack_config/           # BookStack app config (runtime)
    │   ├── keys/
    │   ├── log/
    │   ├── nginx/
    │   ├── php/
    │   └── www/
    ├── bookstack_data/
    │   └── uploads/
    ├── db/
    │   └── bookstack.sql           # Database snapshot
    ├── mariadb_data/               # MariaDB data directory (runtime)
    ├── restore/
    │   ├── restore.bat
    │   ├── restore.sh
    │   └── README.md
    ├── storage/
    │   ├── files/
    │   ├── framework/
    │   ├── images/
    │   ├── themes/
    │   └── uploads/
    ├── control.bat
    ├── docker-compose.yml
    ├── install.bat
    ├── update.bat
    └── README.md
```

---

## Common Commands

**Start services**
```bash
docker compose up -d
```

**Stop services**
```bash
docker compose down
```

**View logs**
```bash
docker compose logs -f bookstack
docker compose logs -f mariadb
```

**Restart BookStack**
```bash
docker compose restart bookstack
```

---

## Backup System

### Create a Backup

**Windows**
```bash
cd backup
backup.bat
```

**Linux / macOS**
```bash
cd backup
./backup.sh
```

**Backup includes:**
- Database backup
- Image backup
- Compressed archive output
- Automatically keeps the last 3 backups

---

## Restore System

### Restore a Backup

**Windows**
```bash
cd restore
restore.bat
```

**Linux / macOS**
```bash
cd restore
./restore.sh
```

**Restore features:**
- Interactive backup selection
- Confirmation prompt before overwrite
- Full database and image restore

---

## Updating BookStack

```bash
docker compose pull bookstack
docker compose up -d --force-recreate
docker exec -it bookstack php artisan migrate --force
```

---

## Versioning System

This project uses a structured versioning approach:

- `VERSION` file — tracks the installed version state
- Git tags — mark available releases
- Update system — enables controlled upgrades

**Version format:**

| Version | Meaning         |
|---------|-----------------|
| v1.0.0  | Initial release |
| v1.1.0  | New features    |
| v1.1.1  | Bug fixes       |
| v2.0.0  | Major rewrite   |

---

## Security Notes

> **Important:** The default credentials are for local/development use only. Update all of the following before deploying to production.

**Change default database credentials in `docker-compose.yml`:**
```yaml
MARIADB_ROOT_PASSWORD: strong-password
MARIADB_PASSWORD: strong-password
```

**Generate a new application key:**
```bash
docker exec -it bookstack php artisan key:generate --show
```

**Additional recommendations:**
- Enable HTTPS via Nginx, Caddy, or Traefik
- Do not expose database ports publicly
- Change the default admin password after first login
- Avoid committing sensitive data to version control

---

## Troubleshooting

**Images not showing**
```bash
docker exec -it bookstack ls -la /config/www/uploads/images

# Fix permissions
docker exec -it bookstack chown -R abc:abc /config/www/uploads/images
```

**Database issues**
```bash
docker compose ps
docker compose logs mariadb
docker compose restart mariadb
```

**Port already in use**

Update the port mapping in `docker-compose.yml`:
```yaml
ports:
  - "8086:80"
```

**Restore failure**

Verify the following:
- `db/bookstack.sql` exists
- DB credentials in the restore script match `docker-compose.yml`
- MariaDB has fully started (wait 10–15 seconds after starting)

---

## Cloning to a New Machine

```bash
git clone https://github.com/alvinmdancy/Bookstack.git
cd Bookstack/bookstack-git
install.bat   # or ./install.sh on Linux/macOS
```

The database, images, and configuration will all be restored automatically.

---

## Environment Variables

| Variable                | Default             | Description        |
|-------------------------|---------------------|--------------------|
| APP_URL                 | http://localhost:8085 | Base URL           |
| DB_HOST                 | mariadb             | Database host      |
| DB_DATABASE             | bookstackapp        | Database name      |
| DB_USERNAME             | bookstack           | Database user      |
| DB_PASSWORD             | bookstackpass       | Database password  |
| MARIADB_ROOT_PASSWORD   | secretrootpass      | Root password      |
| TZ                      | America/New_York    | Timezone           |

---

## Use Cases

- Personal knowledge base
- Team documentation
- Internal wiki
- Developer notes system
- Client documentation portal
- Educational environments

---

## Contributing

1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Submit a pull request

---

## License

For educational and personal use. BookStack itself is licensed under MIT — see [bookstackapp.com](https://www.bookstackapp.com/) for details.
