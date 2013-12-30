<?php
/**
 * @package MtGox PHP API Client v2
 * @author Pathaksa Tongpitak
 * @version 0.1
 * @access public
 * @license http://www.opensource.org/licenses/LGPL-3.0
 */

class MtGoxClient
{
    /**
     * MtGox API Key
     * @var
     */
    private $apiKey;

    /**
     * MtGox API Secret
     * @var
     */
    private $apiSecret;

    /**
     * MtGox API Endpoint
     * @var string
     */
    public $endPoint;

    /**
     * Result Cache
     * @var
     */
    public $result;

    /**
     * Currency Pair
     * @var string
     */
    public $pair;

    /**
     * @param $apiKey
     * @param $apiSecret
     */
    public function __construct($apiKey, $apiSecret)
    {
        define('API_ERROR_EXCEPTION', 1);

        $this->endPoint = 'https://data.mtgox.com/api/2/';
        $this->pair = 'BTCUSD';

        $this->checkRequired($apiKey,'You must specify an API Key');
        $this->checkRequired($apiSecret,'You must specify an API Secret');

        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
    }

    /**
     * Queries a method of the MtGox API.
     *
     * @param string $method
     * @param array $request
     * @return mixed
     * @throws Exception
     */
    public function query($method, array $request = array())
    {
        // API settings
        $apiKey = $this->apiKey;
        $apiSecret = $this->apiSecret;

        // generate a nonce as micro-time, with as-string handling to avoid problems with 32bits systems
        $mt = explode(' ', microtime());
        $request['nonce'] = $mt[1] . substr($mt[0], 2, 6);

        // generate the POST data string
        $postData = http_build_query($request, '', '&');

        // generate Rest Payload
        $restPayload = $method . "\0" . $postData;

        // generate Rest Signature
        $restSign = base64_encode(
            hash_hmac(
                'sha512',
                $restPayload,
                base64_decode($apiSecret),
                true
            )
        );

        // generate the extra headers
        $headers = array(
            'Rest-Key: ' . $apiKey,
            'Rest-Sign: ' . $restSign,
            'Content-type', 'application/x-www-form-urlencoded',
            'Content-Length', strlen($postData)
        );

        // our curl handle (initialize if required)
        static $ch = null;

        if (is_null($ch))
        {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERAGENT,
                'Mozilla/4.0 (compatible; MtGox PHP API Client v2; ' . php_uname('s') . '; PHP/' .
                phpversion() . ')');
        }

        // generate API url
        $url = $this->endPoint . $method;

        // set CURL options
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // run the query
        $response = curl_exec($ch);

        if ($response === false)
            { echo "Gox error, trying again.."; sleep(2); return false; }
            //throw new Exception('Unable to retrieve response: ' . curl_error($ch));

        // decode JSON response
        $result = json_decode($response, true);

        if (!$result) { echo "Gox error, trying again.."; sleep(2); return false; } /*dann edit*/
            //throw new Exception('Invalid response, make sure the API method exists');

        // cache result
        $this->result = $result;

        return $result;
    }

    /**
     * Get account details associated with the current API Authentication.
     *
     * @require API Rights: Get Info
     * @return mixed
     */
    function getInfo() {
        $result = $this->query( $this->pair . '/money/info');

        return $result;
    }

    /**
     * Get the most recent information for a currency pair.
     *
     * @return mixed
     */
    function getTicker() {
        $result = $this->query( $this->pair . '/money/ticker');

        return $result;
    }

    /**
     * Get currency information.
     *
     * @return mixed
     */
    function getCurrency() {
        $result = $this->query( $this->pair . '/money/currency');

        return $result;
    }

    /**
     * Get information on current orders.
     *
     * @return mixed
     */
    function getOrders() {
        $result = $this->query( $this->pair . '/money/orders');

        return $result;
    }

    /**
     * Get an up-to-date quote for a bid or ask transaction.
     *
     * @param string $type
     * @param string $amount
     * @return Array
     */
    function orderQuote($type = 'ask', $amount = '100000000') {
        $result  = $this->query( $this->pair . '/money/order/quote', array(
            'type'      => $type,
            'amount'    => $amount
        ));

        return $result;
    }

    /**
     * Place a bid order of a specific amount and bid price.
     *
     * @param float $amount
     * @param $price
     * @return mixed
     */
    function orderBuy($amount = 0.0001, $price) {
        $result = $this->orderAdd('bid', $amount = 0.0001, $price);
        return $result;
    }

    /**
     * Place an ask order of a specific amount and ask price.
     *
     * @param float $amount
     * @param $price
     * @return mixed
     */
    function orderSell($amount = 0.0001, $price) {
        $result = $this->orderAdd('ask', $amount = 0.0001, $price);
        return $result;
    }

    /**
     * Place an order of a specific amount and bid/ask price.
     *
     * @param $type
     * @param float $amount
     * @param $price
     * @return mixed
     */
    function orderAdd($type, $amount = 0.0001, $price) {
        if ( !in_array($type, array('bid', 'ask')) ) {
            $this->error(API_ERROR_EXCEPTION, 'You must specify a type: bid or ask');
        }

        $this->checkRequired($price,'You must specify a price');

        $result  = $this->query( $this->pair . '/money/order/add', array(
            'type'          => $type,
            'amount_int'    => $amount,
            'price_int'     => $price
        ));

        return $result;
    }

    /**
     * Cancels an order by Order ID.
     *
     * @param $orderId
     * @return mixed
     */
    function orderCancel($orderId) {
        $this->checkRequired($orderId,'You must specify an Order ID');

        $result  = $this->query( $this->pair . '/money/order/cancel', array(
            'oid'          => $orderId
        ));

        return $result;
    }

    /**
     * Returns a unique bitcoin deposit address for a given MtGox account (new each time).
     *
     * @param string $account Account ID fo the following format M12345678X
     * @return mixed
     */
    function generateDepositAddress($account) {
        $this->checkRequired($account,'You must specify an Account ID');

        $result  = $this->query( $this->pair . '/money/bitcoin/get_address', array(
            'account'  => $account
        ));

        return $result;
    }

    /**
     * Generates a new bitcoin address for depositing.
     *
     * @require API Rights: Deposit
     * @param string $description Optional description to display in the account history
     * @param string $ipn Optional IPN URL which will be called with details when bitcoins are received
     * @return mixed
     */
    function getDepositAddress($description = null, $ipn = null) {
        $result  = $this->query( $this->pair . '/money/bitcoin/address', array(
            'description'   => $description,
            'ipn'           => $ipn
        ));

        return $result;
    }

    /**
     * Gets the transaction history of a specified currency wallet.
     *
     * @param string $currency
     * @param int $page
     * @return mixed
     */
    function getWalletHistory($currency = 'BTC', $page = 1) {
        $result  = $this->query( $this->pair . '/money/wallet/history', array(
            'currency'  => $currency,
            'page'      =>  $page
        ));

        return $result;
    }

    /**
     * Sets the current pair for calling methods which require pair prefix.
     *
     * @param string $pair
     * @return $this
     */
    function setPair($pair = 'BTCUSD') {
        $this->pair = $pair;
        return $this;
    }

    /**
     * Check if a variable is not empty.
     *
     * @param $variable
     * @param $message
     */
    function checkRequired($variable, $message) {
        if ($variable == '') {
            $this->error(API_ERROR_EXCEPTION, $message);
        }
    }

    /**
     * Throws errors.
     *
     * @param $type
     * @param $message
     * @throws Exception
     */
    function error($type, $message) {
        throw new Exception($message);
    }
}
