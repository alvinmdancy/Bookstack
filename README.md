# Portable BookStack Distribution

A fully containerized BookStack deployment with automated backup, restore, and update tooling. Designed to clone and run anywhere with minimal setup.

---

## Features

- Docker-based deployment (no PHP/MySQL installation required)
- Environment-based configuration via `.env` — no hardcoded secrets
- Automated backup system (keeps latest 3 backups)
- Interactive restore with auto mode support
- Cross-platform management scripts (Windows `.bat` + Linux `.sh`)
- Version-controlled deployment via `VERSION` file and Git tags
- Tailscale/LAN-friendly `APP_URL` configuration

---

## Prerequisites

### Windows

- Docker Desktop (WSL2 enabled)
- Git for Windows
- Windows 10/11

### Linux

- Docker + Docker Compose
- Git
- `unzip` (`sudo apt install unzip`)

---

## Quick Start

### 1. Clone the repo

```bash
git clone https://github.com/alvinmdancy/Bookstack.git
cd Bookstack
```

### 2. Create your `.env` file

```bash
cp .env.example .env
```

Edit `.env` and set your values:

```env
APP_URL=http://your-hostname-or-ip:8085
APP_KEY=base64:generate-this-below

MARIADB_ROOT_PASSWORD=change-me
MARIADB_DATABASE=bookstackapp
MARIADB_USER=bookstack
MARIADB_PASSWORD=change-me
DB_PASSWORD=change-me
```

### 3. Generate an APP_KEY

```bash
docker run --rm lscr.io/linuxserver/bookstack:latest \
  php /app/www/artisan key:generate --show
```

Paste the output into `APP_KEY` in your `.env`.

### 4. Start BookStack

**Linux**
```bash
docker compose up -d
```

**Windows**
```
control.bat
```

Then open `APP_URL` in your browser.

**Default login**

| Field    | Value       |
|----------|-------------|
| Email    | admin@admin.com |
| Password | password    |

Change these immediately after first login.

---

## Project Structure

```
Bookstack/
├── assets/
│   └── bookstack.ico
├── backup/
│   ├── backup.bat
│   ├── backup.sh
│   └── backups/              # Runtime backups (gitignored)
├── db/
│   └── bookstack.sql         # Optional database snapshot
├── restore/
│   ├── restore.bat
│   └── restore.sh
├── storage/
│   └── images/               # Persisted image uploads
├── .env                      # Local config (gitignored)
├── .env.example              # Template — safe to commit
├── control.bat
├── control.sh
├── docker-compose.yml
├── update.bat
├── update.sh
└── VERSION
```

---

## Management Scripts

### Control Panel

**Linux**
```bash
./control.sh
```

**Windows**
```
control.bat
```

Both show current vs latest version, flag available updates, and provide a menu to start, stop, restart, or update BookStack.

---

## Backup

**Linux**
```bash
bash backup/backup.sh
```

**Windows**
```
cd backup
backup.bat
```

Backs up the database and images, compresses to a `.zip`, and keeps the latest 3 backups automatically.

---

## Restore

**Linux**
```bash
bash restore/restore.sh
```

**Windows**
```
cd restore
restore.bat
```

Both support auto mode by passing a backup path as an argument:

```bash
bash restore/restore.sh /path/to/backup.zip
```

---

## Update

**Linux**
```bash
bash update.sh
```

**Windows**
```
update.bat
```

Pulls latest from GitHub, updates the `VERSION` file, waits for the database, restores the latest backup, and validates the app is reachable.

---

## Common Commands

```bash
# Start
docker compose up -d

# Stop
docker compose down

# Restart
docker compose restart bookstack

# Logs
docker compose logs -f bookstack
docker compose logs -f mariadb
```

---

## Environment Variables

All secrets live in `.env`. See `.env.example` for the full template.

| Variable                | Default               | Description           |
|-------------------------|-----------------------|-----------------------|
| `APP_URL`               | `http://localhost:8085` | Base URL            |
| `APP_KEY`               | —                     | Laravel encryption key |
| `MARIADB_ROOT_PASSWORD` | —                     | MariaDB root password |
| `MARIADB_DATABASE`      | `bookstackapp`        | Database name         |
| `MARIADB_USER`          | `bookstack`           | Database user         |
| `MARIADB_PASSWORD`      | —                     | Database user password |
| `DB_PASSWORD`           | —                     | Same as above (BookStack env) |
| `TZ`                    | `America/New_York`    | Timezone              |

---

## Versioning

- `VERSION` file tracks the currently installed version
- Git tags mark releases
- `control.sh` / `control.bat` compares local vs latest tag and flags updates

| Version | Meaning         |
|---------|-----------------|
| v1.0.0  | Initial release |
| v1.1.0  | New features    |
| v1.1.1  | Bug fixes       |
| v2.0.0  | Major rewrite   |

---

## Troubleshooting

**500 error on first start**
Check that `APP_KEY` is set in `.env` and the container picked it up:
```bash
docker exec -it bookstack env | grep APP_KEY
```

**Images not showing**
```bash
docker exec -it bookstack chown -R abc:abc /config/www/uploads/images
```

**Database not ready**
```bash
docker compose logs mariadb
docker compose restart mariadb
```

**Port already in use**
Change the port in `docker-compose.yml`:
```yaml
ports:
  - "8086:80"
```
Then update `APP_URL` in `.env` to match.

**Artisan commands**
The linuxserver image puts artisan at `/app/www/artisan`:
```bash
docker exec -it bookstack php /app/www/artisan <command>
```

---

## License

For educational and personal use. BookStack itself is licensed under MIT — see [bookstackapp.com](https://www.bookstackapp.com/) for details.