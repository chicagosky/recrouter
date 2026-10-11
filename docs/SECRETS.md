# Secrets inventory

The CakePHP 2 tree (branch `cake2-archive`) stored live credentials in source. **Do not copy those values into CakePHP 5.** Rotate them with the original providers, then set environment variables.

| Old location | What it was | CakePHP 5 variable |
|---|---|---|
| `Config/core.php` `Security.salt` | SHA1 password salt | `CAKE2_SECURITY_SALT` (legacy login only) |
| `Config/core.php` `Security.cipherSeed` | Cipher seed | unused; Cake 5 uses `SECURITY_SALT` |
| `Config/core.php` `CPanel` block | cPanel IP, root user/password, domain, mailbox password | `CPANEL_*` |
| `Config/database.php` | MySQL login | `DATABASE_URL` |
| `Config/email.php` | SMTP usernames/passwords | `EMAIL_*` |
| `Lib/UploadUtil.php` | CloudConvert API key | `CLOUDCONVERT_API_KEY` |
| `Lib/SovrenParser.php` | Sovren account/service key | `SOVREN_ACCOUNT_ID`, `SOVREN_SERVICE_KEY` |

Copy `config/.env.example` to `config/.env` (gitignored) and fill in rotated values. Never commit `config/.env` or `config/app_local.php`.
