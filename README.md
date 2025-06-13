# Laravel E-commerce

A modern e-commerce platform built with Laravel 12, featuring a beautiful UI and robust payment integration with Midtrans.

## Features

-   🛍️ Product Management
-   🛒 Shopping Cart
-   💳 Midtrans Payment Integration
-   👤 User Authentication
-   📦 Order Management
-   📱 Responsive Design
-   🔍 Product Search
-   ❤️ Wishlist
-   📝 Order History
-   📧 Email Notifications

## Requirements

-   PHP >= 8.2
-   Composer
-   MySQL >= 8.0
-   Node.js & NPM
-   Midtrans Account

## Installation

1. Clone the repository:

```bash
git clone https://github.com/yourusername/laravel-ecommerce.git
cd laravel-ecommerce
```

2. Install PHP dependencies:

```bash
composer install
```

3. Install NPM dependencies:

```bash
npm install
```

4. Copy environment file:

```bash
cp .env.example .env
```

5. Generate application key:

```bash
php artisan key:generate
```

6. Configure your database in `.env`:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_ecommerce
DB_USERNAME=root
DB_PASSWORD=
```

7. Configure Midtrans in `.env`:

```
MIDTRANS_MERCHANT_ID=your_merchant_id
MIDTRANS_CLIENT_KEY=your_client_key
MIDTRANS_SERVER_KEY=your_server_key
MIDTRANS_IS_PRODUCTION=false
```

8. Run migrations and seeders:

```bash
php artisan migrate --seed
```

9. Create storage link:

```bash
php artisan storage:link
```

10. Start the development server:

```bash
php artisan serve
```

11. In a separate terminal, start Vite:

```bash
npm run dev
```

## Usage

1. Register a new account or login with existing credentials
2. Browse products and add them to your cart
3. Proceed to checkout
4. Choose shipping method and enter delivery details
5. Complete payment through Midtrans
6. Track your order status in the dashboard

## Payment Integration

This project uses Midtrans for payment processing. To set up Midtrans:

1. Create a Midtrans account at [midtrans.com](https://midtrans.com)
2. Get your API keys from the Midtrans dashboard
3. Configure the callback URLs in your Midtrans dashboard:
    - Success URL: `https://your-domain.com/midtrans/finish`
    - Unfinish URL: `https://your-domain.com/midtrans/unfinish`
    - Error URL: `https://your-domain.com/midtrans/error`
    - Callback URL: `https://your-domain.com/midtrans/callback`

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Acknowledgments

-   [Laravel](https://laravel.com)
-   [Midtrans](https://midtrans.com)
-   [Tailwind CSS](https://tailwindcss.com)
-   [Alpine.js](https://alpinejs.dev)
