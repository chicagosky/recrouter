# Recrouter

Open-source ATS originally built on CakePHP 2.x (2014–2016) and ported to **CakePHP 5.4**.

The CakePHP 2 source is preserved on the `cake2-archive` git branch.

## Requirements

- PHP 8.2–8.5 with `mbstring`, `intl`, `pdo`, `simplexml`
- Composer
- SQLite (default local/test) or MySQL 5.7+

## Setup

```bash
composer install
cp config/.env.example config/.env
# edit config/.env — set DATABASE_URL and rotate any secrets
bin/cake migrations migrate
bin/cake server
```

Seeded login (fresh migrate): `admin@example.com` / `password`.

MySQL example:

```
DATABASE_URL="mysql://user:pass@127.0.0.1/recrouter?encoding=utf8mb4"
```

Legacy CakePHP 2 SHA1 passwords still verify when `CAKE2_SECURITY_SALT` matches the old `Config/core.php` salt; they are rehashed to bcrypt on login.

## Tests

```bash
composer test
```

See [docs/SMOKE_CHECKLIST.md](docs/SMOKE_CHECKLIST.md) and [docs/SECRETS.md](docs/SECRETS.md).
