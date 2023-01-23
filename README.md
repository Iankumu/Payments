## Payments

This is an implementation of the various Payment Gateways in Laravel including `Mpesa`, and `Paypal`.

## Setup

To run this project locally clone the repository and in the project directory,run the following commands:

```
$ cp.env.example .env
$ composer install
$ php artisan key:generate
$ php artisan migrate
$ npm install
$ npm run dev
$ php artisan serve
```

## Technologies Used

-   [Laravel](https://laravel.com/)
-   [Vue](https://vuejs.org/)
-   [Tailwindcss](https://tailwindcss.com/)
-   [InertiaJS](https://inertiajs.com/)
-   [Laravel Mpesa Package](https://github.com/Iankumu/mpesa)
-   [Vite](https://vitejs.dev/)

## Payment Gateways

The application consists of two payment Gateways implementations with more to be added in the future. You can interact with them on [https://payments.iankumu.com](https://payments.iankumu.com).

### Mpesa

The Application Contains a [simple UI](https://payments.iankumu.com/mpesa/stkpush) from which you can interact with the various Mpesa APIs.

### Paypal

The Application Contains a [simple UI](https://payments.iankumu.com/paypal) from which you can interact with the Paypal's [Orders API](https://developer.paypal.com/docs/api/orders/v2/).
