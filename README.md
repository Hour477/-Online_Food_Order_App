# Food Ordering System

A comprehensive Restaurant Management and Food Ordering System built with Laravel 12, Tailwind CSS, and Vite. This system provides an intuitive interface for both administrators and customers to manage orders, menu items, and business settings.

## 🚀 Features

- **Customer Management**: Full CRUD operations with soft delete support and profile image handling.
- **Menu Management**: Categorized menu items with image previews and "Like" functionality.
- **Order System**: Real-time order tracking and management.
- **Admin Dashboard**: Comprehensive stats and reporting tools.
- **Business Settings**: Dynamic configuration for restaurant details, logos, and favicons.
- **Responsive UI**: Modern design that works seamlessly across mobile and desktop.
- **Soft Deletes**: Safety mechanism to prevent permanent data loss.

## 🛠 Prerequisites

Before you begin, ensure you have the following installed:
- **PHP**: ^8.2
- **Composer**: Latest version
- **Node.js & NPM**: Latest version
- **Database**: MySQL or SQLite

## 📥 Installation Steps

Follow these steps to get your development environment set up:

### 1. Clone the Repository
```bash
git clone <repository-url>
cd Food-Ordering-System
```

### 2. Install Dependencies
Install PHP dependencies using Composer:
```bash
composer install
```

Install frontend dependencies using NPM:
```bash
npm install
```

### 3. Environment Configuration
Copy the example environment file and configure your database:
```bash
cp .env.example .env
```
Open the `.env` file and update the database connection details:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=food_ordering_system
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Generate Application Key
```bash
php artisan key:generate
```

### 5. Database Setup
Run the migrations and seed the database with initial data:
```bash
php artisan migrate --seed
```

### 6. Storage Link
Create a symbolic link from `public/storage` to `storage/app/public` to make uploaded images accessible:
```bash
php artisan storage:link
```

### 7. Compile Assets
Build the frontend assets using Vite:
```bash
# For development
npm run dev

# For production
npm run build
```

### 8. Start the Server
Run the local development server:
```bash
php artisan serve
```
The application will be available at `http://127.0.0.1:8000`.

## 🛡 Admin Access
Default admin credentials (if seeded):
- **Email**: `admin@example.com`
- **Password**: `password`

## 🧪 Testing
Run the test suite to ensure everything is working correctly:
```bash
php artisan test
```

## 📄 License
This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
