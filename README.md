# M-Pesa Daraja PHP Library

This is a PHP library designed to integrate with the M-Pesa Daraja API, exclusively for Laravel users but also adaptable for non-Laravel PHP applications.

## Installation

### For Laravel Users

Install the package using Composer:

```sh
composer require sntaks/daraja
```

### For Non-Laravel Users

If you are using plain PHP, you can install the package via Composer:

```sh
composer require sntaks/daraja
```

Then, manually include the Composer autoloader at the beginning of your script:

```php
require __DIR__ . '/vendor/autoload.php';
```

## Configuration

### Laravel Configuration

Publish the configuration file:

```sh
php artisan vendor:publish --tag=config
```

This will create a `config/mpesa.php` file where you can set your API credentials.

### For Non-Laravel Users

Install the package using Composer:

```bash
composer require sntaks/daraja
```

Since non-Laravel projects do not have automatic config publishing, you need to create a configuration file manually. Create a `config/mpesa.php` file in your project's root folder:

```php
<?php

return [
    'apps' => [
        'c2b' => [
            'consumer_key' => env('MPESA_C2B_CONSUMER_KEY'),
            'consumer_secret' => env('MPESA_C2B_CONSUMER_SECRET'),
        ],
        'b2c' => [
            'consumer_key' => env('MPESA_B2C_CONSUMER_KEY'),
            'consumer_secret' => env('MPESA_B2C_CONSUMER_SECRET'),
        ],
        'sandbox' => [
            'consumer_key' => env('MPESA_SANDBOX_CONSUMER_KEY'),
            'consumer_secret' => env('MPESA_SANDBOX_CONSUMER_SECRET'),
        ],
    ],
    'cache_location' => 'cache',
    'callback_method' => 'POST',
    'lnmo' => [
        'short_code' => env('MPESA_LNMO_SHORT_CODE'),
        'callback' => env('MPESA_LMNO_CALLBACK'),
        'passkey' => env('MPESA_LNMO_PASSKEY'),
        'default_transaction_type' => 'CustomerPaybillOnline'
    ],
    'c2b' => [
        'initiator_name' => env('MPESA_C2B_INITIATOR_NAME'),
        'security_credential' => env('MPESA_C2B_SECURITY_CREDENTIAL'),
        'confirmation_url' => env('MPESA_C2B_CONFIRMATION_URL'),
        'validation_url' => env('MPESA_C2B_VALIDATION_URL'),
        'on_timeout' => 'Completed',
        'short_code' => env('MPESA_C2B_SHORTCODE'),
        'test_phone_number' => env('MPESA_C2B_TEST_PHONE_NUMBER'),
        'default_command_id' => 'CustomerPayBillOnline'
    ],
    'b2c' => [
        'initiator_name' => env('MPESA_B2C_INITIATOR_NAME'),
        'security_credential' => env('MPESA_B2C_SECURITY_CREDENTIAL'),
        'short_code' => env('MPESA_B2C_SHORTCODE'),
        'default_command_id' => 'BusinessPayment',
        'test_phone_number' => env('MPESA_B2C_TEST_PHONE_NUMBER'),
        'result_url' => env('MPESA_B2C_RESULT_URL'),
        'timeout_url' => env('MPESA_B2C_TIMEOUT_URL')
    ],
    'transaction_status_b2c' => [
        'result_url' => '',
        'timeout_url' => ''
    ],
    'account_balance_b2c' => [
        'timeout_url'=> '',
        'result_url'=> ''
    ],
    'account_balance_c2b' => [
        'timeout_url'=> '',
        'result_url'=> ''
    ]
];
```

Make sure to set these values in your `.env` file:

```ini
MPESA_C2B_CONSUMER_KEY=your_consumer_key
MPESA_C2B_CONSUMER_SECRET=your_consumer_secret
MPESA_C2B_INITIATOR_NAME=your_initiator_name
MPESA_C2B_SECURITY_CREDENTIAL=your_security_credential
MPESA_C2B_SHORTCODE=your_short_code
MPESA_B2C_CONSUMER_KEY=your_consumer_key
MPESA_B2C_CONSUMER_SECRET=your_consumer_secret
MPESA_B2C_INITIATOR_NAME=your_initiator_name
MPESA_B2C_SECURITY_CREDENTIAL=your_security_credential
MPESA_B2C_SHORTCODE=your_short_code
MPESA_SANDBOX_CONSUMER_KEY=your_consumer_key
MPESA_SANDBOX_CONSUMER_SECRET=your_consumer_secret
MPESA_SANDBOX_INITIATOR_NAME=your_initiator_name
MPESA_SANDBOX_SECURITY_CREDENTIAL=your_security_credential
MPESA_SANDBOX_SHORTCODE=your_short_code
MPESA_TEST_PHONE_NUMBER=254708374149

MPESA_LNMO_SHORT_CODE=your_short_code
MPESA_LNMO_PASSKEY=your_passkey

MPESA_C2B_CONFIRMATION_URL=https://[host]/api/v1/c2b/confirmation.php
MPESA_C2B_VALIDATION_URL=https://[host]/api/v1/c2b/validation.php
MPESA_B2C_RESULT_URL=https://[host]/api/v1/b2c/result.php
MPESA_B2C_TIMEOUT_URL=https://[host]/api/v1/b2c/timeout.php
MPESA_LMNO_CALLBACK=https://[host]/api/v1/lnmo/callback.php
```

## Usage

### Laravel Example

In your controller or service:

```php
use Sntaks\Daraja\Init as Mpesa;
$mpesa = new Mpesa();

```

### Non-Laravel Example

For non-Laravel applications, initialize manually:

```php
require 'vendor/autoload.php';

use Sntaks\Daraja\Init as Mpesa;
$mpesa = new Mpesa();

```

### Finally
```php 
try {
    $user_params = [
        'Amount' => 10,
        'PartyB' => '',
        'Remarks' => 'Test Business Payment'
    ];
    // For B2C Payments
    $response = $mpesa->b2c($user_params);
    // For B2C Account Balance
    $response = $mpesa->b2c_account_balance();
    // For C2B Account Balance
    $response = $mpesa->c2b_account_balance();
    // Get Status of a Single Transaction
    $user_params = [
        'TransactionID' => 'xxxxx'
    ];
    $response = $mpesa->b2c_transaction_status($user_params);*/
    // Register C2B Callbacks, M-Pesa will use these to send notifications
    $response_register = $mpesa->c2b();
    echo $response;
}catch(\Exception $e){
    echo $e->getMessage();
}
```

## License

This package is open-source and available under the [MIT License](LICENSE).

## Support

For any issues or contributions, please open an issue or submit a pull request.
