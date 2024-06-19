# ChatGPT-UI

## Installation

### Run build commands
```
docker compose build
docker compose run --rm web composer install
docker compose run --rm web php artisan migrate
docker compose run --rm transpiler npm install
docker compose run --rm transpiler npm run dev
docker compose up -d
```

### Local address
http://localhost:8000

## Docker Instruction

### Run composer command
```
docker compose run --rm web composer ...
```

### Run node/npm command
```
docker compose run --rm transpiler npm ...
```

### Change .env
Run bellow command to recreate container
```
docker compose up -d web
```

## Other
### Migrating from Vite to Laravel Mix
https://github.com/laravel/vite-plugin/blob/main/UPGRADE.md#migrating-from-vite-to-laravel-mix
