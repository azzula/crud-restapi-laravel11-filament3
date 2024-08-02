# CRUD RestAPI Laravel 11 + Filament 3

Create, Read, Update dan Delete sederhana menggunakan RestAPI pada framework Laravel versi 11 dan paket Filament versi 3

## Installation

Dibutuhkan PHP minimal versi 8.2

```bash
  gh repo clone azzula/crud-restapi-laravel11-filament3
```

```bash
  composer install
```

```bash
  cp .env.example .env
```

```bash
  php artisan key:generate
```

Untuk membuat user :

```bash
  php artisan make:filament-user
```

## API Reference

#### Get all items

```http
  GET /api/admin/products
```

#### Get item

```http
  GET /api/admin/products/${uuid}
```

## Documentation

[Laravel](https://laravel.com/docs/11.x)
[Livewire](https://livewire.laravel.com/docs/quickstart)
[Tailwind CSS](https://tailwindcss.com/docs/installation)
[Filament](https://filamentphp.com/docs/3.x/panels/installation)

## Feedback

Jika Anda memiliki masukan, silakan hubungi saya di m.sufyan@raharja.info
