<?php
namespace Sntaks\Daraja;

use Exception;
use Illuminate\Support\Facades\Log;


class Core{

    public function __construct(){}

    /**
     * Used for Lipa Na MPESA Online (LMNO)
     * Pushes a payment request to customers Phone
     * @param string $url
     * @param array $userParams
     * @return bool|string
     */
    public static function requestSTK(string $url, array $userParams=[]): bool|string {
        $configParams = [
            'BusinessShortCode' => Config::get('mpesa.lnmo.short_code'),
            'Password' => Auth::secureCredentials(),
            'Timestamp' => date('YmdHis'),
            'TransactionType' => 'CustomerPayBillOnline',
            'PartyB' => Config::get('mpesa.lnmo.short_code'),
            'CallBackURL' => Config::get('mpesa.lnmo.callback'),
            'TransactionDesc' => 'Wallet Deposit'
        ];
        return self::request($url, $configParams, $userParams);
    }

    /**
     * Sets up parameters for simulating C2B payment
     * @param string $url
     * @param array $userParams
     * @return bool|string
     */
    public static function requestC2BSimulate(string $url, array $userParams=[]): bool|string
    {
        $configParams = [
            'CommandID' => Config::get('mpesa.c2b.default_command_id'),
            'Amount' => '10',
            'Msisdn' => '254708374149',
            'BillRefNumber' => 'TRIPPINMAD',
            'ShortCode' => Config::get('mpesa.c2b.short_code')
        ];
        return self::request($url, $configParams, $userParams);
    }

    /**
     * Sets up parameters for registering C2B Callback URLS.
     * Called only ONCE.
     * @param string $url
     * @param array $userParams
     * @return bool|string
     */
    public static function requestC2BRegister(string $url, array $userParams=[]): bool|string
    {
        $configParams = [
            'ShortCode' => Config::get('mpesa.c2b.short_code'),
            'ResponseType' => 'Completed',
            'ConfirmationURL' => Config::get('mpesa.c2b.confirmation_url'),
            'ValidationURL' => Config::get('mpesa.c2b.validation_url')
        ];
        return self::request($url, $configParams, $userParams);
    }

    /**
     * Sets Up B2C Parameters
     * Generated access_token based on environment
     * @param string $url
     * @param array $userParams
     * @return bool|string
     */
    public static function requestB2C(string $url, array $userParams=[]): bool|string
    {
        $pass = Config::get('mpesa.b2c.security_credential');
        $configParams = [
            'InitiatorName' => Config::get('mpesa.b2c.initiator_name'),
            'SecurityCredential' => self::computeSecurityCredentials($pass, env('MPESA_PRODUCTION')),
            'CommandID' => Config::get('mpesa.b2c.default_command_id'),
            'PartyA' => Config::get('mpesa.b2c.short_code'),
            'QueueTimeOutURL' => Config::get('mpesa.b2c.timeout_url'),
            'ResultURL' => Config::get('mpesa.b2c.result_url'),
        ];
        return self::request($url, $configParams, $userParams);
    }

    /**
     * Reverse a B2C transaction.
     * @param string $url
     * @param array $userParams
     * @return string
     */
    public static function requestB2CReverse(string $url, array $userParams=[]): string
    {
        $pass = Config::get('mpesa.b2c.security_credential');
        $configParams = [
            'InitiatorName' => Config::get('mpesa.b2c.initiator_name'),
            /*'SecurityCredential' => 'gU1mSoy5+lTGYMG1+QUcCDqIxnHV+hY+1eOwGoguZofl47mYjVO5hDfS7Tm6cu1QXGOyfO7wvBA6EcLzVQqqbKpKWllod+4S0JV3qWvBXbc9CcfTPmCajo+KnvAtqXTLNWWMJYXJtuAcVMXpPfGcqPw+t4Fuyk0rnnSyKwcU+69E8eaL6/yiYTZlz4hoN2OinpbX2KE4iBFsuNAaOq+Jeb0/vp7CrtIqyvUeyvSTDl7LWk37KwphhMc+HKfisa9YGygdhx+u3YvxeqjNfuCmsaufUCRSwIY2XGOnC5O0X6MkX3mjGapuTkHsnmCjm04EcJJmhuS9Kl6su9wAW+CcLw==',*/
            'SecurityCredential' => self::computeSecurityCredentials($pass, env('MPESA_PRODUCTION')),
            'CommandID' => 'TransactionReversal',
            "TransactionID" => "[original trans_id]",
            "Amount" => "[trans amount]",
            "ReceiverParty" => "600610",
            "ReceiverIdentifierType" => "4",
            "ResultURL" => "",
            "QueueTimeOutURL" => "",
            "Remarks" => "",
            "Occasion" => "",
        ];
        return self::request($url, $configParams, $userParams);
    }

    /**
     * Return the status of transaction given the transaction ID
     * @param string $url
     * @param array $userParams
     * @return bool|string
     */
    public static function requestTransactionStatusB2C(string $url, array $userParams=[]): bool|string
    {
        //$userParams = ['TransactionID' => ''];
        $pass = Config::get('mpesa.b2c.security_credential');
        $configParams = [
            'Initiator' => Config::get('mpesa.b2c.initiator_name'),
            'SecurityCredential' => self::computeSecurityCredentials($pass, env('MPESA_PRODUCTION')),
            'CommandID' => 'TransactionStatusQuery',
            'PartyA' => '509296',
            'IdentifierType' => '4',
            'Remarks' => 'Transaction Status Query',
            'Occasion' => '',
            'QueueTimeOutURL' => Config::get('mpesa.transaction_status_b2c.timeout_url'),
            'ResultURL' => Config::get('mpesa.transaction_status_b2c.result_url'),
        ];

        return self::request($url, $configParams, $userParams);
    }

    /**
     * @param string $url
     * @param array $userParams
     * @return bool|string
     */
    public static function requestAccountBalanceB2C(string $url, array $userParams=[]): bool|string
    {
        $pass = Config::get('mpesa.b2c.security_credential');
        $configParams = [
            'Initiator' => Config::get('mpesa.b2c.initiator_name'),
            'SecurityCredential' => self::computeSecurityCredentials($pass, env('MPESA_PRODUCTION')),
            'CommandID' => 'AccountBalance',
            'PartyA' => '509296',
            'IdentifierType' => '4',
            'Remarks' => 'Account Balance Query',
            'QueueTimeOutURL' => Config::get('mpesa.account_balance_b2c.timeout_url'),
            'ResultURL' => Config::get('mpesa.account_balance_b2c.result_url'),
        ];

        return self::request($url, $configParams, $userParams);
    }


    /**
     * @param string $url
     * @param array $userParams
     * @return bool|string
     */
    public static function requestAccountBalanceC2B(string $url, array $userParams=[]): bool|string
    {
        $pass = Config::get('mpesa.c2b.security_credential');
        $configParams = [
            'Initiator' => Config::get('mpesa.c2b.initiator_name'),
            'SecurityCredential' => self::computeSecurityCredentials($pass, env('MPESA_PRODUCTION')),
            'CommandID' => 'AccountBalance',
            'PartyA' => '597716',
            'IdentifierType' => '4',
            'Remarks' => 'Account Balance Query',
            'QueueTimeOutURL' => Config::get('mpesa.account_balance_c2b.timeout_url.php'),
            'ResultURL' => Config::get('mpesa.account_balance_c2b.result_url.php'),
        ];

        return self::request($url, $configParams, $userParams);
    }


    /**
     * Make a POST request to the given URL with the given parameters.
     *
     * @param string $url The URL to make the request to.
     * @param array $configParams The base parameters to be included in the request.
     * @param array $userParams The custom parameters to be included in the request.
     * @return string The JSON response from the request.
     */
    public static function request(string $url='', array $configParams=[], array $userParams=[]): string
    {
        $requestData = array_merge($configParams, $userParams);
        $accessToken = Auth::authenticate('c2b',env('MPESA_ENV'));
        try{
            $ch = curl_init($url);
            // Set the HTTP headers
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer '.$accessToken,
                'Content-Type: application/json'
            ]);

            // Set the POST request options
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestData));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $response = curl_exec($ch);
            curl_close($ch);
            return $response;
        }catch (Exception $e) {
            Log::channel('mpesa')->error('Error Response: ' . $e->getMessage());
            Log::channel('mpesa')->error('Full Response: ' . json_encode($e));
            return json_encode([
                'error' => true,
                'message' => $e->getMessage()
            ]);
        }
    }


    /**
     * Compute Security Credentials
     *
     * @param string $initiatorPass The password that will be encrypted
     * @param bool $isProduction Whether to use the production or sandbox certificate
     * @return string The encrypted password
     */
    public static function computeSecurityCredentials(string $initiatorPass, bool $isProduction = false): string {
        $certPath = $isProduction ? "productionCert.txt" : "sandboxCert.txt";
        $kf = fopen(dirname(__FILE__)."/$certPath", "r") or die("Unable to open file!");
        $pubKey = fread($kf,filesize(dirname(__FILE__)."/$certPath")); fclose($kf);
        $publicKey = openssl_pkey_get_public($pubKey);
        openssl_public_encrypt($initiatorPass, $encrypted, $publicKey, OPENSSL_PKCS1_PADDING);
        return base64_encode($encrypted);
    }
}
