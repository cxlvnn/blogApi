# BlogApi

A RESTful Blog API built with **Laravel 13**, featuring user authentication via **Laravel Sanctum**, post management, comments, likes, reads, and bookmarks. Designed to be lightweight and easy to spin up locally or via Docker.

---

## Table of Contents

1. [Features](#features)
2. [Tech Stack](#tech-stack)
3. [Project Structure](#project-structure)
4. [Getting Started](#getting-started)
   - [Option A: Docker (Recommended)](#option-a-docker-recommended)
   - [Option B: Local Setup (Manual)](#option-b-local-setup-manual)
5. [Environment Configuration](#environment-configuration)
6. [API Endpoints](#api-endpoints)
7. [Authentication & CSRF](#authentication--csrf)
8. [Testing](#testing)
9. [Troubleshooting](#troubleshooting)

---

## Features

- **User Authentication**: Registration, login, logout, password change, and account deletion using Laravel Sanctum.
- **Posts**: Create, read, update, delete blog posts (with pagination).
- **Comments**: Nested comments on individual posts.
- **Interactions**: Like/unlike posts and bookmark posts.
- **Read Tracking**: Track post reads per user.
- **Author Profile**: Retrieve author information via a dedicated endpoint.
- **API Resources**: Clean JSON responses using Laravel Eloquent API Resources.
- **Request Validation**: Form Request classes for robust input validation.
- **Database Migrations**: Full versioning with relationships (users, posts, comments, likes, reads, bookmarks).

---

## Tech Stack

| Layer            | Technology                           |
| ---------------- | ------------------------------------ |
| Language         | PHP 8.4                              |
| Framework        | Laravel 13                           |
| Authentication   | Laravel Sanctum                      |
| Frontend Build   | Vite + Tailwind CSS 4                |
| API Docs         | `andreaselia/laravel-api-to-postman` |
| Testing (Dev)    | Pest PHP                             |
| Containerization | Docker + Docker Compose              |

---

## Project Structure

```
├── app/
│   ├── Http/Controllers/
│   │   ├── Auth/AuthController.php       # Registration, login, logout, password, me
│   │   ├── AuthorController.php          # Get author by name
│   │   ├── BookmarkController.php        # Bookmark actions + list
│   │   ├── CommentController.php         # CRUD for post comments
│   │   ├── LikeController.php            # Like/unlike a post
│   │   ├── PostController.php            # CRUD for posts
│   │   └── UserController.php            # Update user profile
│   ├── Http/Requests/                    # Form Request validation classes
│   ├── Http/Resources/                   # API Resources (Post, Comment, User, etc.)
│   ├── Models/
│   │   ├── User.php
│   │   ├── Post.php
│   │   ├── Comment.php
│   │   ├── Like.php
│   │   ├── Read.php
│   │   └── Bookmark.php
│   └── Providers/
├── bootstrap/                            # App bootstrapping
├── config/                               # Laravel configs
├── database/
│   ├── factories/
│   ├── migrations/                         # Users, Posts, Comments, Likes, Reads, Bookmarks
│   └── seeders/                           # Database seeders
├── docker/
│   └── entrypoint.sh                       # Container bootstrap script
├── public/                                 # Webroot
├── resources/
│   ├── css/app.css
│   └── js/app.js
├── routes/
│   ├── api.php                             # Core API routes
│   ├── web.php                             # Minimal web route (Hello World)
│   └── console.php                          # Artisan commands
├── .env.example                            # Template environment file
├── composer.json
├── package.json
├── Dockerfile                              # Container image definition
├── docker-compose.yml                      # Docker orchestration
└── README.md
```

---

## Getting Started

You have **two ways** to get the application up and running: **Docker** (quickest and portable) or a **manual local setup**.

---

### Option A: Docker (Recommended)

You need [Docker](https://docs.docker.com/get-docker/) and [Docker Compose](https://docs.docker.com/compose/) installed.

#### 1. Clone the repository

```bash
git clone https://github.com/cxlnv/blogApi.git
cd blogApi
```

#### 2. Build and start the container

```bash
docker compose up -d
```

> **What this does:**
> - Builds the image (`php:8.4-cli-bookworm`) with all required extensions.
> - Installs PHP and Node dependencies.
> - Creates an SQLite database file automatically.
> - Runs database migrations.
> - Starts the PHP development server on port `8000`.

#### 3. Verify the server is running

```bash
curl http://localhost:8000
# Expected: Hello World
```

#### 4. Stopping the container

```bash
docker compose down        # Stop and remove containers
```

To completely remove volumes (including the SQLite database):

```bash
docker compose down -v
```

#### 5. Rebuilding after code changes

```bash
docker compose up -d --build
```

#### Docker Configuration Notes

- **Database**: Uses **SQLite by default** inside the container (`database/database.sqlite`). No external database server is needed.
- **Environment Variables**: All important Laravel env settings are injected directly via `docker-compose.yml` (no `.env` file needed).
- **Logs**: The container outputs Laravel logs to `stderr` for easy inspection.
- **PHP Version**: Built on PHP `8.4` to match the Composer lock requirements (some dependencies require `>= 8.4`).

---

### Option B: Local Setup (Manual)

#### Prerequisites

- PHP `>= 8.4` (with `pdo_sqlite`, `mbstring`, `xml`, `zip`, `bcmath`, `gd`)
- [Composer](https://getcomposer.org/)
- [Node.js](https://nodejs.org/) (for Vite/Tailwind asset building)
- A terminal with `git`

#### 1. Clone the repository

```bash
git clone https://github.com/cxlnv/blogApi.git
cd blogApi
```

#### 2. Install PHP dependencies

```bash
composer install
```

#### 3. Install Node dependencies and build assets

```bash
npm install
npm run build
```

#### 4. Configure environment

```bash
cp .env.example .env
php artisan key:generate
```

#### 5. Set up the database

This project defaults to **MySQL** in `.env.example`, but you can easily switch to **SQLite** by updating `.env`:

```dotenv
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/blogApi/database/database.sqlite
```

Then create the database file (for SQLite):

```bash
touch database/database.sqlite
```

Run migrations (and optionally seed):

```bash
php artisan migrate
php artisan db:seed   # Optional: seeds a sample user
```

> If you keep MySQL, ensure your local MySQL service is running and update credentials in `.env` accordingly.

#### 6. Start the development server

```bash
php artisan serve
```

The API will be available at:

```
http://127.0.0.1:8000
```

---

## Environment Configuration

Key `.env` variables used by the application:

| Variable            | Description                                     | Default           |
| ------------------- | ----------------------------------------------- | ----------------- |
| `APP_ENV`           | Application environment (`local`, `production`) | `local`           |
| `APP_KEY`           | Encryption key (auto-generated)                 | —                 |
| `APP_URL`           | Base URL                                        | `http://localhost`|
| `DB_CONNECTION`     | Database driver (`sqlite` or `mysql`)           | `sqlite` (Docker) |
| `DB_DATABASE`       | SQLite path or MySQL database name              | `database.sqlite` |
| `SESSION_DRIVER`    | Session handler (Docker uses `file`)            | `file`            |
| `CACHE_STORE`       | Cache driver                                    | `file`            |
| `QUEUE_CONNECTION`  | Queue driver                                    | `sync`            |
| `BROADCAST_CONNECTION` | Broadcasting driver                         | `log`             |

---

## API Endpoints

### Authentication (Public / Guest)

| Method | Endpoint                | Description                   |
| ------ | ----------------------- | ----------------------------- |
| `POST` | `/api/register`           | Register a new user           |
| `POST` | `/api/login`              | Authenticate existing user    |

### Authenticated Routes (`auth:sanctum`)

| Method     | Endpoint                                  | Description                          |
| ---------- | ----------------------------------------- | ------------------------------------ |
| `GET`      | `/api/user`                               | Simple auth check                    |
| `GET`      | `/api/me`                                 | Get authenticated user profile         |
| `POST`     | `/api/user`                               | Update authenticated user            |
| `DELETE`   | `/api/user`                               | Delete authenticated account         |
| `PUT`      | `/api/user/password`                      | Change password                      |
| `DELETE`   | `/api/logout`                             | Logout current session               |
| `GET`      | `/api/author/{name}`                      | Get author by name (letters only)    |
| `GET`      | `/api/posts`                              | List all posts (latest, paginated)   |
| `POST`     | `/api/posts`                              | Create a new post                    |
| `GET`      | `/api/posts/{post}`                       | Show a specific post                   |
| `PATCH`    | `/api/posts/{post}`                       | Update a post                        |
| `DELETE`   | `/api/posts/{post}`                       | Delete a post                        |
| `POST`     | `/api/posts/{post}/bookmark`              | Bookmark / unbookmark a post         |
| `GET`      | `/api/posts/{post}/comments`              | List comments on a post                |
| `POST`     | `/api/posts/{post}/comments`              | Create a comment                       |
| `GET`      | `/api/posts/{post}/comments/{comment}`    | Show a specific comment                |
| `PATCH`    | `/api/posts/{post}/comments/{comment}`    | Update a comment                       |
| `DELETE`   | `/api/posts/{post}/comments/{comment}`    | Delete a comment                       |
| `GET`      | `/api/posts/{post}/likes`                 | List post likes                        |
| `POST`     | `/api/posts/{post}/like`                  | Like / unlike a post                 |
| `GET`      | `/api/me/bookmarks`                       | List authenticated user's bookmarks    |

### Default Web Route

| Method | Endpoint | Description         |
| ------ | -------- | ------------------- |
| `GET`  | `/`      | Returns "Hello World" |

---

## Authentication & CSRF

This application uses **Laravel Sanctum** with **stateful cookie-based authentication** for the API routes.

- **Sanctum CSRF Cookie**: Before making `POST` / `PUT` / `PATCH` / `DELETE` requests from a frontend client, hit `GET /sanctum/csrf-cookie` and include the returned `X-XSRF-TOKEN` header.
- **Session Middleware**: `EnsureFrontendRequestsAreStateful` is applied to API routes in `bootstrap/app.php`, so authentication works via standard web sessions when the `Accept: application/json` header is sent.

If you are testing with tools like **Postman** or **cURL**, make sure to:
1. Call `GET /sanctum/csrf-cookie` first.
2. Extract the `XSRF-TOKEN` cookie.
3. Pass it back as `X-XSRF-TOKEN` on state-changing requests.

---

## Testing

The project includes Pest PHP for unit and feature testing.

### Running tests (Local)

```bash
php artisan test
# or
vendor/bin/pest
```

### Running tests (Docker)

```bash
docker compose exec api php artisan test
```

---

## Troubleshooting

### Build Error: `Package 'oniguruma' not found`
This was addressed by explicitly installing `libonig-dev` in the Dockerfile.

### Build Error: `Your php version (8.3.31) does not satisfy that requirement`
The `composer.lock` requires PHP `>= 8.4` (specifically Symfony 8.0 components). The Dockerfile explicitly targets `php:8.4-cli-bookworm`.

### `Class "Laravel\Pail\PailServiceProvider" not found`
This can occur if cached bootstrap files (`bootstrap/cache/packages.php` or `services.php`) from a local dev run are copied into the Docker image where `pail` (a dev-only package) is not installed. The Dockerfile now explicitly removes those cache files before building the image.

### MySQL Connection Refused in Docker
If you switch Docker to use MySQL, uncomment the `db` service in `docker-compose.yml`, ensure `DB_CONNECTION=mysql` and `DB_HOST=db` are set, and run `docker compose up -d --build`.

### Port Already in Use
If `8000` is taken, change the port mapping in `docker-compose.yml`:

```yaml
ports:
  - "8080:8000"
```

Access it at `http://localhost:8080`.

---

## License

This is a personal project by [cxlnv](https://github.com/cxlnv). No specific license is provided by default.
