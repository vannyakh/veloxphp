# VeloxPHP Framework

A lightweight, modern PHP framework for building web applications and APIs with built-in admin dashboard and webhook support.

## Features

- 🚀 Modern MVC Architecture
- 🔒 Built-in Security Features
- 📊 AdminLTE Dashboard Integration
- 🔗 Webhook System
- 🔄 RESTful API Support
- 📝 Eloquent-style ORM
- ⚡ Fast Routing System
- 🛠️ Dependency Injection Container
- 📨 Event System
- 🔍 Middleware Support
- 🎨 Blade-like Template Engine
- 📦 Redis Integration
- 📊 Monitoring Tools

## Requirements

- PHP 8.0 or higher
- MySQL 5.7+ or MariaDB 10.3+
- Composer
- Node.js & NPM (for admin dashboard)
- Redis (optional, for session handling)

## Installation

1. Create a new project:
```bash
composer create-project veloxphp/veloxphp your-project
```

2. Configure your environment:
```bash
cp .env.example .env
```

3. Install dependencies:
```bash
composer install
npm install
```

4. Set up the database:
```bash
php velox migrate
php velox db:seed
```

5. Start the development server:
```bash
php velox serve
```

## Web Server Configuration

### Apache
Place the following in your `.htaccess`:
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /
    RewriteRule ^index\.php$ - [L]
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule . /index.php [L]
</IfModule>
```

### Nginx
Add this to your server block:
```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

## Admin Dashboard

The framework includes a pre-built admin dashboard using AdminLTE:

1. Access the dashboard at `/admin`
2. Default credentials:
   - Username: admin@example.com
   - Password: password

### Customizing the Dashboard

1. Configure dashboard settings:
```bash
php velox publish:config admin
```

2. Modify the menu in `config/admin.php`:
```php
'menu' => [
    [
        'header' => 'MAIN NAVIGATION',
        'items' => [
            [
                'text' => 'Dashboard',
                'icon' => 'fas fa-tachometer-alt',
                'url' => '/admin/dashboard'
            ]
        ]
    ]
]
```

## Webhook System

The framework includes a robust webhook system supporting multiple providers:

1. Configure webhooks in `.env`:
```env
WEBHOOKS_ENABLED=true
WEBHOOK_SIGNING_SECRET=your-secret
GITHUB_WEBHOOK_SECRET=github-secret
STRIPE_WEBHOOK_SECRET=stripe-secret
```

2. Register webhook handlers in `config/webhooks.php`:
```php
'handlers' => [
    'github' => [
        'secret' => env('GITHUB_WEBHOOK_SECRET'),
        'events' => [
            'push' => \App\Webhooks\Handlers\GitHubPushHandler::class
        ]
    ]
]
```

3. Create a webhook handler:
```php
class GitHubPushHandler extends AbstractWebhookHandler
{
    public function handle(array $payload, array $headers)
    {
        // Handle webhook
    }
}
```

4. Test your webhook:
```bash
curl -X POST https://your-domain.com/webhooks/github \
    -H "Content-Type: application/json" \
    -H "X-Webhook-Event: push" \
    -H "X-Webhook-Signature: signature" \
    -d '{"event":"data"}'
```

## Security

The framework includes several security features:

- CSRF Protection
- XSS Prevention
- SQL Injection Protection
- Rate Limiting
- Request Sanitization
- JWT Authentication
- Role-based Access Control

## Monitoring

Built-in monitoring support with Prometheus and Grafana:

1. Start monitoring:
```bash
docker-compose up -d prometheus grafana
```

2. Access dashboards:
- Grafana: http://localhost:3000
- Prometheus: http://localhost:9090

## Contributing

1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Support

- Documentation: [https://veloxphp.com/docs](https://veloxphp.com/docs)
- Issues: [GitHub Issues](https://github.com/veloxphp/veloxphp/issues)
- Discord: [Join our community](https://discord.gg/veloxphp)

## Credits

Created by [Thecam Zones](mailto:vannya168@thecamzones.pro)