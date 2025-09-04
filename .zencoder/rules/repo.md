---
description: Repository Information Overview
alwaysApply: true
---

# Linqify Information

## Summary
Linqify is a web application built with Laravel framework for managing and organizing links. It provides functionality for categorizing links, user authentication, and dashboard visualization.

## Structure
- **app**: Core application code with Models, Controllers, and Providers
- **bootstrap**: Application bootstrap files
- **config**: Configuration files for Laravel
- **database**: Database migrations, seeders, and factories
- **public**: Publicly accessible files and entry point
- **resources**: Frontend assets, Vue components, and views
- **routes**: API and web route definitions
- **storage**: Application storage for logs and cache
- **tests**: PHPUnit test files

## Language & Runtime
**Language**: PHP with TypeScript/JavaScript frontend
**PHP Version**: 8.2+ (Running on 8.4.11)
**Laravel Version**: 12.27.1
**Build System**: Composer for PHP, Vite for frontend
**Package Manager**: Composer for PHP, npm for JavaScript

## Dependencies
**Main PHP Dependencies**:
- laravel/framework (^12.0)
- inertiajs/inertia-laravel (^2.0)
- laravel/sanctum (^4.0)
- laravel/tinker (^2.10.1)
- tightenco/ziggy (^2.0)

**Main JavaScript Dependencies**:
- vue (^3.5.21)
- @inertiajs/vue3 (^2.1.4)
- @headlessui/vue (^1.7.23)
- @heroicons/vue (^2.2.0)
- chart.js (^4.5.0)
- pinia (^3.0.3)

**Development Dependencies**:
- laravel/breeze (^2.3)
- laravel/pint (^1.24)
- laravel/sail (^1.41)
- phpunit/phpunit (^11.5.3)
- tailwindcss (^3.2.1)
- typescript (^5.6.3)
- vite (^7.0.4)

## Build & Installation
```bash
# PHP dependencies
composer install

# JavaScript dependencies
npm install

# Development
npm run dev

# Production build
npm run build

# Start development server
php artisan serve

# Run development environment (server, queue, logs, vite)
composer run-script dev
```

## Database
**Default Configuration**: SQLite for development/testing
**Migrations**: Located in database/migrations
**Models**: User, Category, Link, LinkType, UserSettings
**Seeders**: DatabaseSeeder, DemoDataSeeder, LinkTypeSeeder

## Testing
**Framework**: PHPUnit
**Test Location**: tests/Feature and tests/Unit
**Configuration**: phpunit.xml
**Run Command**:
```bash
php artisan test
# or
composer test
```