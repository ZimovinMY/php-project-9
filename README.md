### Hexlet tests and linter status:
[![Actions Status](https://github.com/ZimovinMY/php-project-9/actions/workflows/hexlet-check.yml/badge.svg)](https://github.com/ZimovinMY/php-project-9/actions)

# Hexlet Project 3

## Deploy Link:
[Render App](https://php-project-9-fl89.onrender.com)

# Page analyzer

Simple web application built with Slim Framework.

## Requirements
- PHP 8.0+
- Composer
- PostgreSQL

## Installation
1. Clone the repository
2. Run `composer install`
3. Set up PostgreSQL locally and configure DATABASE_URL (e.g., `export DATABASE_URL=postgresql://username:password@localhost:5432/mydb`)
4. Load the database schema: `psql -a -d $DATABASE_URL -f database.sql`
5. Start the server with `make start`

## Development
- Run linter: `make lint`
- Start server: `make start`

## Features
- Uses Slim Framework with Twig templating and DI Container for dependency injection
- PostgreSQL database with urls table
- Bootstrap 5 for styling via CDN
- URL analyzer with form validation, flash messages, and URL listing

## Deployment
- Deploy on Render.com with DATABASE_URL set in Environment Variables
- Start Command: `make start`

## Notes
- SQL file for database schema: `database.sql`
- Environment variable DATABASE_URL required for database connection