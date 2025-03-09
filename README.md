# Financial Loan

System for financial loans.

## Development

Run the following command to start the development server:

```bash
docker compose up -d
```

Run the migration in the `migrations` dir.

Access the following URL to view the application: http://localhost:8080

### Logging

To view the PHP logs, run the following command:

```bash
docker compose logs -f web
```

To view error logs, run the following command:

```bash
docker compose exec web tail -f /var/log/apache2/error.log
```

### Database

To generate a new password for the user:

```bash
docker compose exec web php -r "echo password_hash('1234', PASSWORD_DEFAULT) . PHP_EOL;"
```

Change the `1234` value to the desired password. Save this value in the `senha_crip` field in the `usuarios` table.
