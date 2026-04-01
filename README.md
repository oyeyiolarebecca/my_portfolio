<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Contact Form Email (So You Receive Messages)

When someone submits the contact form (`POST /api/contact`), the app:

- Saves the message to the database (`contact_messages` table)
- Sends an email notification to you (the portfolio owner)

### 1) Set where the email should go

In your `.env`, set:

- `CONTACT_TO_ADDRESS="your-email@example.com"`
- `CONTACT_TO_NAME="Rebecca"` (optional)

### 2) Configure a real mailer (otherwise nothing is delivered)

By default the app uses `MAIL_MAILER=log`, which writes emails to logs instead of sending them.

To actually receive emails, pick a mailer and configure it in `.env`.

#### Gmail (SMTP)

1) Turn on 2‑Step Verification on your Google account, then create an **App Password**.
2) Set these in `.env`:

```env
MAIL_MAILER=smtp
MAIL_URL="smtp://YOUR_GMAIL_ADDRESS:YOUR_APP_PASSWORD@smtp.gmail.com:587?encryption=tls"
MAIL_FROM_ADDRESS="YOUR_GMAIL_ADDRESS"
MAIL_FROM_NAME="Rebecca"
```

Note: If your password has special characters, URL-encode it (or use `MAIL_USERNAME` / `MAIL_PASSWORD` instead of `MAIL_URL`).

#### SMTP (Any Provider)

Set your SMTP credentials in `.env`, for example:

```env
MAIL_MAILER=smtp
MAIL_URL=null
MAIL_HOST=smtp.your-provider.com
MAIL_PORT=587
MAIL_USERNAME=your_smtp_username
MAIL_PASSWORD=your_smtp_password
MAIL_FROM_ADDRESS="no-reply@your-domain.com"
MAIL_FROM_NAME="Rebecca"
```

#### Resend

```env
MAIL_MAILER=resend
RESEND_API_KEY=your_resend_api_key
MAIL_FROM_ADDRESS="no-reply@your-domain.com"
MAIL_FROM_NAME="Rebecca"
```

#### Postmark (requires an extra package)

Install:

```bash
composer require symfony/postmark-mailer
```

Then set:

```env
MAIL_MAILER=postmark
POSTMARK_API_KEY=your_postmark_api_key
```

#### Mailgun (requires an extra package)

Install:

```bash
composer require symfony/mailgun-mailer
```

Then configure via `MAIL_URL` (recommended) according to your Mailgun SMTP/API settings.

#### Amazon SES

```env
MAIL_MAILER=ses
AWS_ACCESS_KEY_ID=...
AWS_SECRET_ACCESS_KEY=...
AWS_DEFAULT_REGION=us-east-1
```

#### Provider diversity (failover / round robin)

You can mix providers without code changes:

```env
MAIL_MAILER=failover
MAIL_FAILOVER_MAILERS=smtp,resend,log
```

### 3) Quick local check (no SMTP)

If you keep `MAIL_MAILER=log`, you can confirm the email is being “sent” by checking:

- `storage/logs/laravel.log`

## Deployment

See `DEPLOYMENT.md`.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
