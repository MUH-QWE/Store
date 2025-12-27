<<<<<<< HEAD
# STORE Project

A complete e-commerce web application built with PHP, HTML, CSS, and JS.

## Setup

1. **Database Config**:
   - Edit `app/config/env.php` with your MySQL credentials.
   - Default: `localhost`, `store_db`, `root`, ``.

2. **Initialization**:
   - Run `php migrate.php` from the terminal to create the database tables and default admin user.
   - **Default Admin**: `admin@store.com` / `admin123`

3. **Running the App**:
   - Serve the project folder using Apache or PHP built-in server.
   - `php -S localhost:8000` from the root directory.
   - Visit `http://localhost:8000/web/index.php`.

## Structure

- **/app**: Backend logic (API, Config, Helpers, Middleware).
- **/web**: Frontend interface (HTML/PHP, CSS, JS).
- **/app/api**: REST API endpoints.

## Features

- User Authentication (Login/Register/JWT)
- Product Management (Admin)
- Shopping Cart
- Responsive Design

## Documentation

See `/app/config` for environment settings.
See `/app/sql` for database schema.
=======
# STORE Project

A complete e-commerce web application built with PHP, HTML, CSS, and JS.

## Setup

1. **Database Config**:
   - Edit `app/config/env.php` with your MySQL credentials.
   - Default: `localhost`, `store_db`, `root`, ``.

2. **Initialization**:
   - Run `php migrate.php` from the terminal to create the database tables and default admin user.
   - **Default Admin**: `admin@store.com` / `admin123`

3. **Running the App**:
   - Serve the project folder using Apache or PHP built-in server.
   - `php -S localhost:8000` from the root directory.
   - Visit `http://localhost:8000/web/index.php`.

## Structure

- **/app**: Backend logic (API, Config, Helpers, Middleware).
- **/web**: Frontend interface (HTML/PHP, CSS, JS).
- **/app/api**: REST API endpoints.

## Features

- User Authentication (Login/Register/JWT)
- Product Management (Admin)
- Shopping Cart
- Responsive Design

## Documentation

See `/app/config` for environment settings.
See `/app/sql` for database schema.
>>>>>>> e27ec8f7fb7c7231818d1513b72f8cc50b28affd
