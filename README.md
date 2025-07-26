# Laravel Vue Full Stack Test Project

A full stack application built with Laravel 12 and Vue 3, featuring multi-authentication, posts management, image upload functionality, and modern UI design. This project demonstrates a complete Single Page Application (SPA) architecture with robust API testing coverage.

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
-   **Pagination**: Server-side pagination for efficient handling of large datasets
-   **Image Upload**: Support for featured images with preview and removal functionality
-   **Image Validation**: File type and size validation for uploaded images

### Frontend Features

-   **Vue 3 Composition API**: Modern reactive framework
-   **Vue Router**: Client-side routing with navigation guards
-   **TailwindCSS**: Modern, utility-first CSS framework
-   **Axios Integration**: HTTP client for API communication

### Backend Features

-   **Laravel 12**: Latest Laravel framework
-   **API Resources**: RESTful API endpoints with proper data formatting
-   **Form Validation**: Server-side validation with custom error messages
-   **Database Seeding**: Sample data for testing
-   **Polymorphic Relationships**: Flexible post authorship system
-   **Comprehensive Testing**: 52+ test cases covering all functionality
-   **Image Storage**: Secure file upload and storage management


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

### Regular Users Example

-   **Email**: john@example.com | **Password**: password


## 🔐 API Endpoints

### Authentication

-   `POST /api/admins/login` - Admin login
-   `POST /api/users/login` - User login
-   `POST /api/admins/logout` - Admin logout (authenticated)
-   `POST /api/users/logout` - User logout (authenticated)

### Posts

-   `GET /api/posts` - List posts (with search, filter, pagination)
-   `POST /api/posts` - Create new post (authenticated)
-   `GET /api/posts/{id}` - Get specific post (authenticated)
-   `PUT /api/posts/{id}` - Update post (authenticated)
-   `DELETE /api/posts/{id}` - Delete post (authenticated)
-   `GET /api/posts/stats` - Get posts statistics (authenticated)

## 🎨 Frontend Routes

-   `/` - Welcome page with login options
-   `/admins/login` - Admin login page
-   `/users/login` - User login page
-   `/admins` - Admin dashboard (authenticated)
-   `/users` - User dashboard (authenticated)
-   `/posts` - Posts list (authenticated)
-   `/posts/create` - Create new post (authenticated)
-   `/posts/{id}/edit` - Edit post (authenticated)

## 🚀 Advanced Features

### Image Management

-   **Upload Support**: File picker for images
-   **Preview**: Real-time image preview before upload
-   **Validation**: File type (jpg, jpeg, png, gif) and size (max 2MB) validation
-   **Removal**: Remove existing images with confirmation
-   **Storage**: Secure file storage with automatic cleanup

### Dashboard Statistics

-   **Real-time Stats**: Total, published, and draft post counts
-   **Role-based Views**: Different stats for admins vs users
-   **Auto-refresh**: Statistics update automatically

### Search & Filtering

-   **Full-text Search**: Search by title and content
-   **Status Filtering**: Filter by published/draft status
-   **Combined Queries**: Search and filter simultaneously

### Pagination

-   **Server-side Pagination**: Efficient handling of large datasets
-   **Configurable Page Size**: Adjustable posts per page
-   **Navigation**: First, previous, next, last page controls

## 🧪 Testing

### Running All Tests

```bash
php artisan test
```

### Running Specific Test Suites

```bash
# Run only feature tests
php artisan test tests/Feature/

# Run only unit tests
php artisan test tests/Unit/

# Run specific test file
php artisan test tests/Feature/PostControllerTest.php
```

## 🔧 Development

### Building for Production

```bash
npm run build
```

### Development Server

```bash
# Start Laravel server
php artisan serve

# Start Vite dev server (in separate terminal)
npm run dev
```

## 📦 API Documentation

### Postman Collection

A complete Postman collection is included: [`Laravel_Vue_Test_API.postman_collection.json`](Laravel_Vue_Test_API.postman_collection.json)

**Setup Instructions:**

1. Download the collection by clicking the link above
2. Import the collection into Postman
3. Set the `base_url` variable to your local server (e.g., `http://localhost:8000`)
4. Use the provided sample users for authentication
