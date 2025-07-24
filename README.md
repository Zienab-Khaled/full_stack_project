# Laravel Vue Full Stack Test Project

A comprehensive full stack application built with Laravel 12 and Vue 3, featuring multi-authentication, posts management, and modern UI design.

## 🚀 Features

### Authentication & Authorization

-   **Multi-Auth System**: Separate authentication for Admins and Users
-   **Laravel Sanctum**: API token-based authentication
-   **Role-based Access Control**: Different permissions for different user types
-   **Secure Login Pages**: Separate login interfaces for admins and users

### Posts Management

-   **CRUD Operations**: Create, Read, Update, Delete posts
-   **Role-based Permissions**:
    -   Admins can manage all posts
    -   Users can only manage their own posts
-   **Post Status**: Draft and Published states
-   **Search & Filtering**: Search posts by title/content and filter by status
-   **Pagination**: Efficient handling of large datasets

### Frontend Features

-   **Vue 3 Composition API**: Modern reactive framework
-   **Vue Router**: Client-side routing with navigation guards
-   **TailwindCSS**: Modern, utility-first CSS framework
-   **Responsive Design**: Mobile-first approach
-   **Axios Integration**: HTTP client for API communication

### Backend Features

-   **Laravel 12**: Latest Laravel framework
-   **API Resources**: RESTful API endpoints
-   **Form Validation**: Server-side validation with custom error messages
-   **Database Seeding**: Sample data for testing
-   **Polymorphic Relationships**: Flexible post authorship system

## 🛠️ Tech Stack

### Backend

-   **Laravel 12** - PHP framework
-   **Laravel Sanctum** - API authentication
-   **MySQL** - Database
-   **Eloquent ORM** - Database abstraction

### Frontend

-   **Vue 3** - Progressive JavaScript framework
-   **Vue Router 4** - Official router for Vue.js
-   **Vite** - Build tool and dev server
-   **TailwindCSS** - Utility-first CSS framework
-   **Axios** - HTTP client

## 📋 Prerequisites

Before running this project, make sure you have the following installed:

-   **PHP 8.2+**
-   **Composer**
-   **Node.js 18+**
-   **npm** or **yarn**
-   **MySQL** or **MariaDB**

## 🚀 Installation

### 1. Clone the Repository

```bash
git clone https://github.com/Zienab-Khaled/full_stack_project.git
cd full_stack_project
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install Node.js Dependencies

```bash
npm install
```

### 4. Environment Setup

```bash
cp .env.example .env
php artisan key:generate
php artisan storage:link
```

### 5. Database Configuration

Update your `.env` file with your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_vue_test
DB_USERNAME=
DB_PASSWORD=
```

### 6. Run Database Migrations

```bash
php artisan migrate
```

### 7. Seed the Database

```bash
php artisan db:seed
```

### 8. Build Frontend Assets

```bash
npm run build
```

### 9. Start the Development Server

```bash
php artisan serve
```

In a separate terminal, start the Vite development server:

```bash
npm run dev
```

## 👥 Sample Users

The application comes with pre-seeded users for testing:

### Admin Users

-   **Email**: admin@example.com | **Password**: password
-   **Email**: john.admin@example.com | **Password**: password
-   **Email**: jane.admin@example.com | **Password**: password

### Regular Users

-   **Email**: john@example.com | **Password**: password
-   **Email**: jane@example.com | **Password**: password
-   **Email**: bob@example.com | **Password**: password
-   **Email**: alice@example.com | **Password**: password

## 🗂️ Project Structure

```
laravel-vue-test/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   │   ├── AdminAuthController.php
│   │   │   │   └── UserAuthController.php
│   │   │   └── PostController.php
│   │   └── Requests/
│   │       └── PostRequest.php
│   └── Models/
│       ├── Admin.php
│       ├── Post.php
│       └── User.php
├── database/
│   ├── migrations/
│   │   ├── create_admins_table.php
│   │   └── create_posts_table.php
│   └── seeders/
│       ├── AdminSeeder.php
│       ├── UserSeeder.php
│       └── PostSeeder.php
├── resources/
│   ├── js/
│   │   ├── components/
│   │   │   ├── App.vue
│   │   │   ├── auth/
│   │   │   │   ├── AdminLogin.vue
│   │   │   │   └── UserLogin.vue
│   │   │   ├── admin/
│   │   │   │   └── AdminDashboard.vue
│   │   │   ├── user/
│   │   │   │   └── UserDashboard.vue
│   │   │   └── posts/
│   │   │       ├── PostsList.vue
│   │   │       └── PostForm.vue
│   │   └── app.js
│   ├── css/
│   │   └── app.css
│   └── views/
│       └── welcome.blade.php
├── routes/
│   └── api.php
└── config/
    └── auth.php
```

## 🔐 API Endpoints

### Authentication

-   `POST /api/admins/login` - Admin login
-   `POST /api/users/login` - User login
-   `POST /api/admins/logout` - Admin logout (authenticated)
-   `POST /api/users/logout` - User logout (authenticated)
-   `GET /api/admins/user` - Get admin user info (authenticated)
-   `GET /api/users/user` - Get user info (authenticated)

### Posts

-   `GET /api/posts` - List posts (with search, filter, pagination)
-   `POST /api/posts` - Create new post (authenticated)
-   `GET /api/posts/{id}` - Get specific post (authenticated)
-   `PUT /api/posts/{id}` - Update post (authenticated)
-   `DELETE /api/posts/{id}` - Delete post (authenticated)

## 🎨 Frontend Routes

-   `/` - Redirects to user login
-   `/admins/login` - Admin login page
-   `/users/login` - User login page
-   `/admins` - Admin dashboard (authenticated)
-   `/users` - User dashboard (authenticated)
-   `/posts` - Posts list (authenticated)
-   `/posts/create` - Create new post (authenticated)
-   `/posts/{id}/edit` - Edit post (authenticated)

## 🔧 Development

### Running Tests

```bash
php artisan test
```

### Building for Production

```bash
npm run build
```


