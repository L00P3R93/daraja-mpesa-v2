<?php
return [
    /* Credentials
     * *************************************************************************************************************
     * The MPESA API Credentials
     */
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
    /* File Cache Location
     * **************************************************************************************************************
     * Caching location on the local disk
     */
    'cache_location' => 'cache',

    /* Callback Method
     * **************************************************************************************************************
     * Server Request Method to be used on the Callback URL
     */
    'callback_method' => 'POST',

    /* LNM Online Configurations
     * *************************************************************************************************************
     */
    'lnmo' => [
        /*
         * Paybill Number
         * *********************************************************************************************************
         * The Registered Paybill Number that is used as the Merchant ID
         */
        'short_code' => env('MPESA_LNMO_SHORT_CODE'),
        /*
         * STK Push Callback URL
         * *********************************************************************************************************
         * A fully qualified endpoint that will be queried by Safaricom API
         * on completion or failure of a push transaction
         */
        'callback' => env('MPESA_LMNO_CALLBACK'),
        /*
         * SAG Passkey
         * *********************************************************************************************************
         * The secre SAG Passkey generated by Safaricom on successful
         * registrations of the Merchant's Paybill Number.
         */
        'passkey' => env('MPESA_LNMO_PASSKEY'),
        /*
         * Default Transaction Type
         * *********************************************************************************************************
         * This is the Default Transaction Type set on every STK Push request
         */
        'default_transaction_type' => 'CustomerPaybillOnline'
    ],

    /*
     * C2B Configuration
     * *************************************************************************************************************
     * A fully qualified endpoint that will be queried by Safaricom API
     * on completion or failure of the transaction.
     */
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

    /*
     * B2C Configuration
     * *************************************************************************************************************
     * A fully qualified endpoint that will be queried by Safaricom API
     * on completion or failure of the transaction.
     */
    'b2c' => [
        'initiator_name' => env('MPESA_B2C_INITIATOR_NAME'),
        'security_credential' => env('MPESA_B2C_SECURITY_CREDENTIAL'),
        'short_code' => env('MPESA_B2C_SHORTCODE'),
        'default_command_id' => 'BusinessPayment',
        'test_phone_number' => env('MPESA_B2C_TEST_PHONE_NUMBER'),
        'result_url' => env('MPESA_B2C_RESULT_URL'),
        'timeout_url' => env('MPESA_B2C_TIMEOUT_URL')
    ],

    /*
     * Transaction Status API Config
     * *************************************************************************************************************
     */
    'transaction_status_b2c' => [
        'result_url' => '',
        'timeout_url' => ''
    ],

    /*
     * Account Balance B2C
     * *************************************************************************************************************
     */
    'account_balance_b2c' => [
        'timeout_url'=> '',
        'result_url'=> ''
    ],

    /*
     * Account Balance C2B
     * *************************************************************************************************************
     */
    'account_balance_c2b' => [
        'timeout_url'=> '',
        'result_url'=> ''
    ]
];
