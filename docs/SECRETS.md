# Secrets inventory

The CakePHP 2 tree originally stored live credentials and candidate resumes in source. Those values are **not** copied into CakePHP 5. Rotate anything that was ever committed, then set environment variables locally.

| Old location | What it was | CakePHP 5 variable |
|---|---|---|
| `Config/core.php` `Security.salt` | SHA1 password salt | `CAKE2_SECURITY_SALT` (legacy login only) |
| `Config/core.php` `Security.cipherSeed` | Cipher seed | unused; Cake 5 uses `SECURITY_SALT` |
| `Config/core.php` `CPanel` block | cPanel IP, root user/password, domain, mailbox password | `CPANEL_*` |
| `Config/database.php` | MySQL login | `DATABASE_URL` |
| `Config/email.php` | SMTP usernames/passwords | `EMAIL_*` |
| `Lib/UploadUtil.php` | CloudConvert API key | `CLOUDCONVERT_API_KEY` |
| `Lib/SovrenParser.php` | Sovren account/service key | `SOVREN_ACCOUNT_ID`, `SOVREN_SERVICE_KEY` |
| `Lib/RchilliParser.php` | RChilli user key / sub-user | `RCHILLI_USER_KEY`, `RCHILLI_SUB_USER_ID` |
| `Lib/RecruiterPlusParser.php` | RecruiterPlus secret | `RECRUITERPLUS_SECRET_KEY` |
| `Upload/` and `webroot/resume*.xml` | Candidate resumes and parsed XML (PII) | not used; keep `uploads/` gitignored |

## CakePHP 5

Copy `config/.env.example` to `config/.env` (gitignored) and fill in **rotated** values. Never commit `config/.env` or `config/app_local.php`. Example files must stay placeholder-only.

## CakePHP 2 (`cake2-archive`)

Committed configs read environment variables or empty defaults. Local overrides belong in:

- `Config/local.php` (gitignored) — salt, cipher seed, integration keys
- `Config/database.local.php` (gitignored) — MySQL login

Copy the `*.example` files beside them. Do not commit candidate files under `Upload/` or parsed resumes in `webroot/`.

## Git history

Removing files from the working tree does **not** remove them from git history. The initial commit still contains credentials and resumes. Publish Cake 5 from a new orphan branch or rewritten history, and keep `cake2-archive` private until it is rewritten.
