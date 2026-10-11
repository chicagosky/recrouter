# Recrouter

Recrouter was an idea born from my frustrations dealing with hiring process at a large IT consulting firm. While core HR had access to an ATS, it was often poorly adopted and in many instances completely ignored. I wrote Recrouter to solve my problems in a generic manner and assumed that others would benefit from the same as well. At one point there were about 100+ active users from small businesses on the platform. Eventually I closed it down as my needs were different and the cost of operating the platform as a freebie was not sustainable. Review `Introducing Recrouter` to learn more about the platform and it business.

Originally built on CakePHP 2.x (2014–2016) and ported to **CakePHP 5.4** with the help of AI. The platform had very intuitive and innovative features that can be extracted and used as standalone components in future platforms.

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

Legacy CakePHP 2 SHA1 passwords still verify when `CAKE2_SECURITY_SALT` is set locally to the old salt. Do not commit that value. Passwords are rehashed to bcrypt on login unless dual-boot is enabled.

## Tests

```bash
composer test
```

See [docs/SMOKE_CHECKLIST.md](docs/SMOKE_CHECKLIST.md) and [docs/SECRETS.md](docs/SECRETS.md).
