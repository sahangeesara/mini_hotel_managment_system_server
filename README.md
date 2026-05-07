# Mini Hotel Management System - Server

A comprehensive REST API backend for managing hotels, bookings, and reservations. Built with Laravel 10, this system provides a robust platform for hotel administrators and customers to manage hotel information, availability, and bookings.

## 📋 Table of Contents

- [Features](#features)
- [Requirements](#requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [API Endpoints](#api-endpoints)
- [Database Schema](#database-schema)
- [Project Structure](#project-structure)
- [Development](#development)
- [Testing](#testing)
- [License](#license)

## ✨ Features

- **Hotel Management**: Create, read, update, and delete hotel listings
- **Hotel Filtering**: Search and filter hotels by location, city, country, and price range
- **Image Management**: Upload and manage hotel images with URL generation
- **Amenities Management**: Store and retrieve hotel amenities
- **Availability Tracking**: Track hotel availability status
- **User Authentication**: Built-in authentication with Laravel Sanctum
- **RESTful API**: Complete REST API for easy integration with frontend applications
- **Database Migrations**: Automated database schema management
- **Error Handling**: Comprehensive error handling and validation

## 🔧 Requirements

- PHP >= 8.1
- Composer
- MySQL/PostgreSQL or SQLite
- Node.js (for Vite asset compilation)

## 📦 Installation

### 1. Clone the Repository
```bash
git clone <repository-url>
cd mini_hotel_managment_system_server
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configure Database
Update your `.env` file with database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hotel_management
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Run Migrations
```bash
php artisan migrate
```

### 6. (Optional) Seed Database
```bash
php artisan db:seed
```

## ⚙️ Configuration

### Key Configuration Files
- `config/database.php` - Database configuration
- `config/auth.php` - Authentication settings
- `config/sanctum.php` - API token configuration
- `config/cors.php` - CORS settings

### Storage Configuration
Images are stored in `storage/app/public/`. Make sure to create the symbolic link:
```bash
php artisan storage:link
```

This creates a link from `public/storage` to `storage/app/public/`, allowing images to be accessed via HTTP.

## 🖼️ Image Access

### Uploading Hotel Images

When creating or updating a hotel, include the image file in the multipart form data:

```bash
curl -X POST http://localhost:8000/api/hotels \
  -F "hotel_name=Grand Hotel" \
  -F "location=Downtown" \
  -F "city=New York" \
  -F "country=USA" \
  -F "price_per_night=150" \
  -F "star_rating=5" \
  -F "amenities[0]=WiFi" \
  -F "amenities[1]=Pool" \
  -F "description=Luxury hotel" \
  -F "image=@/path/to/image.jpg"
```

### Accessing Images

Images are stored in `storage/app/public/hotel/images/` and can be accessed via:
```
http://localhost:8000/storage/public/hotel/images/{filename}
```

The API response includes the `image_url` field with the full URL:
```json
{
  "id": 1,
  "hotel_name": "Grand Hotel",
  "image": "public/hotel/images/1234567890_image.jpg",
  "image_url": "http://localhost:8000/storage/public/hotel/images/1234567890_image.jpg",
  ...
}
```

### Troubleshooting Image Issues

**If images are not visible:**

1. **Ensure symbolic link exists:**
   ```bash
   php artisan storage:link
   ```
   This should create `public/storage` directory linked to `storage/app/public`

2. **Check storage permissions:**
   ```bash
   chmod -R 755 storage/
   chmod -R 755 public/storage
   ```

3. **Verify file exists:**
   - Check the `storage/app/public/hotel/images/` directory
   - Ensure files are readable

4. **Clear Laravel cache:**
   ```bash
   php artisan cache:clear
   php artisan config:clear
   ```

5. **Check CORS configuration:**
   - Ensure `config/cors.php` includes `'storage/*'` in paths

## 🔌 API Endpoints

### Hotels Endpoints

#### Get All Hotels
```
GET /api/hotels
```

#### Get Single Hotel
```
GET /api/hotels/{id}
```

#### Create Hotel
```
POST /api/hotels
```

**Request Body:**
```json
{
  "hotel_code": "HTL001",
  "hotel_name": "Grand Hotel",
  "location": "Downtown",
  "city": "New York",
  "country": "USA",
  "price_per_night": 150.00,
  "star_rating": 5,
  "amenities": ["WiFi", "Pool", "Gym"],
  "description": "Luxury hotel in the heart of the city",
  "is_available": true
}
```

#### Update Hotel
```
PUT /api/hotels/{id}
```

#### Delete Hotel
```
DELETE /api/hotels/{id}
```

#### Filter Hotels
```
GET /api/hotel?city=New York&country=USA&min_price=100&max_price=500
```

## 📊 Database Schema

### Hotels Table
- `id` - Primary Key (UUID)
- `hotel_code` - Unique hotel code
- `hotel_name` - Name of the hotel
- `location` - Detailed location/address
- `city` - City name
- `country` - Country name
- `image` - Image file path
- `image_url` - Generated image URL
- `price_per_night` - Price per night (decimal)
- `star_rating` - Star rating (1-5)
- `amenities` - JSON array of amenities
- `description` - Hotel description
- `is_available` - Availability status (boolean)
- `created_at` - Timestamp
- `updated_at` - Timestamp

### Users Table
- `id` - Primary Key
- `name` - User name
- `email` - User email (unique)
- `password` - Hashed password
- `email_verified_at` - Email verification timestamp
- `created_at` - Timestamp
- `updated_at` - Timestamp

## 📁 Project Structure

```
mini_hotel_managment_system_server/
├── app/
│   ├── Http/
│   │   ├── Controllers/    # API Controllers
│   │   ├── Kernel.php      # HTTP Middleware
│   │   └── Middleware/     # Custom Middleware
│   ├── Models/             # Eloquent Models
│   │   ├── Hotels.php
│   │   └── User.php
│   ├── Exceptions/         # Exception Handlers
│   └── Providers/          # Service Providers
├── database/
│   ├── migrations/         # Database Migrations
│   ├── factories/          # Model Factories
│   └── seeders/            # Database Seeders
├── routes/
│   ├── api.php            # API Routes
│   ├── web.php            # Web Routes
│   └── channels.php       # Broadcasting Channels
├── config/                # Configuration Files
├── storage/               # File Storage
├── tests/                 # Unit & Feature Tests
└── vendor/                # Composer Dependencies
```

## 💻 Development

### Start Development Server
```bash
php artisan serve
```

The API will be available at `http://localhost:8000`

### Compile Assets
```bash
npm run dev    # Development mode
npm run build  # Production build
```

### Run Tests
```bash
php artisan test
```

### Use Laravel Tinker (REPL)
```bash
php artisan tinker
```

## 🧪 Testing

Run the test suite:
```bash
php artisan test
```

Run specific test file:
```bash
php artisan test tests/Feature/ExampleTest.php
```

## 📝 License

The Mini Hotel Management System is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 🤝 Support

For support, please create an issue in the repository or contact the development team.

---

**Built with ❤️ using Laravel 10 & PHP 8.1**
