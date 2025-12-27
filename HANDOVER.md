<<<<<<< HEAD
# STORE - System Walkthrough

## Overview
This is a complete e-commerce application with a PHP backend and HTML/JS frontend.

## 1. Setup
1.  **Database**: Ensure you have MySQL running.
2.  **Configuration**: Check `app/config/env.php` and update `DB_USER`, `DB_PASS`, etc.
3.  **Migration**: Run the migration script to create tables and default admin.
    ```bash
    php migrate.php
    ```
    *Default Admin Credentials:* `admin@store.com` / `admin123`

## 2. Running the Application
Serve the application from the root directory. You can use the PHP built-in server:
```bash
php -S localhost:8000
```
Then navigate to: [http://localhost:8000](http://localhost:8000)

## 3. Features Implemented
### Backend (`/app`)
-   **API**: RESTful endpoints in `app/api/` covering all major entities.
-   **Auth**: JWT-based (simulated) authentication/session management.
-   **Database**: PDO helper with SQL schema in `app/sql/`.

### Frontend (`/web`)
-   **Storefront**: Home, Products, Product Details (with variants & reviews).
-   **User Area**: Cart, Wishlist, Checkout, Order History.
-   **Admin Panel**: Manage Products (add/delete), Users, and View Orders.

## 4. Key Files
-   `migrate.php`: Database setup.
-   `app/bootstrap.php`: App initialization.
-   `web/js/ALL.js`: API client and state management.
=======
# STORE - System Walkthrough

## Overview
This is a complete e-commerce application with a PHP backend and HTML/JS frontend.

## 1. Setup
1.  **Database**: Ensure you have MySQL running.
2.  **Configuration**: Check `app/config/env.php` and update `DB_USER`, `DB_PASS`, etc.
3.  **Migration**: Run the migration script to create tables and default admin.
    ```bash
    php migrate.php
    ```
    *Default Admin Credentials:* `admin@store.com` / `admin123`

## 2. Running the Application
Serve the application from the root directory. You can use the PHP built-in server:
```bash
php -S localhost:8000
```
Then navigate to: [http://localhost:8000](http://localhost:8000)

## 3. Features Implemented
### Backend (`/app`)
-   **API**: RESTful endpoints in `app/api/` covering all major entities.
-   **Auth**: JWT-based (simulated) authentication/session management.
-   **Database**: PDO helper with SQL schema in `app/sql/`.

### Frontend (`/web`)
-   **Storefront**: Home, Products, Product Details (with variants & reviews).
-   **User Area**: Cart, Wishlist, Checkout, Order History.
-   **Admin Panel**: Manage Products (add/delete), Users, and View Orders.

## 4. Key Files
-   `migrate.php`: Database setup.
-   `app/bootstrap.php`: App initialization.
-   `web/js/ALL.js`: API client and state management.
>>>>>>> e27ec8f7fb7c7231818d1513b72f8cc50b28affd
