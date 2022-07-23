<?php

namespace Bregananta\Nicepay;

use Carbon\Carbon;
use Faker\Core\Number;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Nicepay
{
    /**
     * @param $bankCd
     * @param $vacctValidDt
     * @param $vacctValidTm
     * @param $amt
     * @param $referenceNo
     * @param $goodsNm
     * @param $billingNm
     * @param $billingPhone
     * @param $billingEmail
     * @param string $billingAddr
     * @param string $billingCity
     * @param string $billingState
     * @param string $billingPostCd
     * @param string $billingCountry
     * @param string $deliveryNm
     * @param string $deliveryPhone
     * @param string $deliveryAddr
     * @param string $deliveryCity
     * @param string $deliveryState
     * @param string $deliveryPostCd
     * @param string $deliveryCountry
     * @param string $description
     * @param string $reqDomain
     * @param string $reqServerIP
     * @param string $userIP
     * @param string $userSessionID
     * @param string $userAgent
     * @param string $userLanguage
     * @param array $cartData
     * @param string $merFixAcctld
     * @param string $currency
     * @return string
     */
    public function registerVa(string $bankCd, string $vacctValidDt, string $vacctValidTm, $amt, string $referenceNo,
                               string $goodsNm, string $billingNm, string $billingPhone,
                               string $billingEmail, string $billingAddr = '', string $billingCity = '',
                               string $billingState = '', string $billingPostCd = '', string $billingCountry = '',
                               string $deliveryNm ='', string $deliveryPhone = '', string $deliveryAddr = '',
                               string $deliveryCity = '', string $deliveryState = '', string $deliveryPostCd = '',
                               string $deliveryCountry = '', string $description = '', string $reqDomain = '',
                               string $reqServerIP = '', string $userIP = '', string $userSessionID = '',
                               string $userAgent = '', string $userLanguage = '',
                               string $merFixAcctld = '', string $currency = 'IDR')
    {
        $timeStamp = Carbon::parse(now('Asia/Jakarta'));
        $timeStamp = $timeStamp->format('YmdHis');

        $dbprocess_url = config('nicepay-config.dbprocess_url');
        if (config('nicepay-config.dev') == true) {
            $dbprocess_url = config('nicepay-config.dev_dbprocess_url');
        }

        $body = [
            'timeStamp' => $timeStamp,
            'iMid' => $this->getImid(),
            'payMethod' => '02',
            'currency' => $currency,
            'amt' => (int)$amt,
            'referenceNo' => $referenceNo,
            'goodsNm' => $goodsNm,
            'billingNm' => $billingNm,
            'billingPhone' => $billingPhone,
            'billingEmail' => $billingEmail,
            'billingAddr' => $billingAddr,
            'billingCity' => $billingCity,
            'billingState' => $billingState,
            'billingPostCd' => $billingPostCd,
            'billingCountry' => $billingCountry,
            'deliveryNm' => $deliveryNm,
            'deliveryPhone' => $deliveryPhone,
            'deliveryAddr' => $deliveryAddr,
            'deliveryCity' => $deliveryCity,
            'deliveryState' => $deliveryState,
            'deliveryPostCd' => $deliveryPostCd,
            'deliveryCountry' => $deliveryCountry,
            'description' => $description,
            'dbProcessUrl' => $dbprocess_url,
            'merchantToken' => $this->getMerchantToken($timeStamp, $referenceNo, (int)$amt),
            'reqDomain' => $reqDomain,
            'reqServerIP' => $reqServerIP,
            'userIP' => $userIP,
            'userSessionID' => $userSessionID,
            'userAgent' => $userAgent,
            'userLanguage' => $userLanguage,
            'bankCd' => $bankCd,
            'vacctValidDt' => $vacctValidDt,
            'vacctValidTm' => $vacctValidTm,
            'merFixAcctId' => $merFixAcctld
        ];

        if (config('nicepay-config.log') == true) {
            Log::info('VA Register Payload Sent : '. PHP_EOL);
            Log::info($body);
        }

        return $this->apiRequest('nicepay/direct/v2/registration', $body);
    }

    /**
     * @param string $tXid
     * @param string $payMethod
     * @param string $cancelMsg
     * @param $amt
     * @param string $cancelUserId
     * @param string $cancelUserInfo
     * @param string $cancelServerIp
     * @param string $cancelUserIp
     * @param string $cancelType
     * @param string $referenceNo
     * @param $cancelRetryCnt
     * @param string $preauthToken
     * @param string $worker
     * @return string
     */
    public function cancelTransaction(string $tXid, string $payMethod, string $cancelMsg, $amt, string $cancelUserId, 
                                    string $cancelUserInfo = '', string $cancelServerIp = '', string $cancelUserIp = '', 
                                    string $cancelType = 1, string $referenceNo = '', $cancelRetryCnt = 1, 
                                    string $preauthToken = '', string $worker = '')
    {
        $timeStamp = Carbon::parse(now('Asia/Jakarta'));
        $timeStamp = $timeStamp->format('YmdHis');

        $body = [
            'timeStamp' => $timeStamp,
            'tXid' => $tXid,
            'iMid' => $this->getImid(),
            'payMethod' => $payMethod,
            'cancelType' => $cancelType,
            'cancelMsg' => $cancelMsg,
            'merchantToken' => $this->getMerchantToken($timeStamp, $tXid, (int)$amt),
            'preauthToken' => $preauthToken,
            'amt' => $amt,
            'cancelServerIp' => $cancelServerIp,
            'cancelUserId' => $cancelUserId,
            'cancelUserIp' => $cancelUserIp,
            'cancelUserInfo' => $cancelUserInfo,
            'cancelRetryCnt' => $cancelRetryCnt,
            'referenceNo' => $referenceNo,
            'worker' => $worker
        ];

        if (config('nicepay-config.log') == true) {
            Log::info('Cancel Transaction Payload Sent : '. PHP_EOL);
            Log::info($body);
        }

        return $this->apiRequest('nicepay/direct/v2/cancel', $body);
    }

    /**
     * @param $tXid
     * @param $amt
     * @param $merchantToken
     * @return boolean
     */
    public function verifyIncomingNotification($tXid, $amt, $merchantToken)
    {
        if ($merchantToken == hash('sha256', config('nicepay-config.imid') . $tXid . $amt . config('nicepay-config.merchant_key'))) {
            return true;
        }

        return false;
    }

    /**
     * @param $referenceNo
     * @param $tXid
     * @param $amt
     * @return string
     */
    public function statusInquiry($referenceNo, $tXid, $amt)
    {
        $timeStamp = Carbon::parse(now('Asia/Jakarta'));
        $timeStamp = $timeStamp->format('YmdHis');

        $body = [
            'timeStamp' => $timeStamp,
            'iMid' => $this->getImid(),
            'merchantToken' => $this->getMerchantToken($timeStamp, $referenceNo, (int)$amt),
            'tXid' => $tXid,
            'referenceNo' => $referenceNo,
            'amt' => (int)$amt
        ];

        if (config('nicepay-config.log') == true) {
            Log::info('VA Payment Status Inquiry Payload Sent : '. PHP_EOL);
            Log::info($body);
        }

        return $this->apiRequest('nicepay/direct/v2/inquiry', $body);
    }

    /**
     * @param $timestamp
     * @param $referenceNo
     * @param $amount
     * @return false|string
     */
    protected function getMerchantToken($timestamp, $referenceNo, $amount)
    {
        $iMid = $this->getImid();
        $merchantKey = $this->getMerchantKey();

        return hash('sha256', $timestamp . $iMid . $referenceNo . $amount . $merchantKey);
    }

    /**
     * @param $path
     * @param $body
     * @return string
     */
    protected function apiRequest($path, $body)
    {
        $url = $this->getBaseUrl() .'/'. $path;

        if (config('nicepay-config.log') == true) {
            Log::info('Nicepay Endpoint : '. PHP_EOL);
            Log::info($url);
        }

        return Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->post($url, $body)->body();
    }

    /**
     * return string
     */
    protected function getImid()
    {
        $imid = config('nicepay-config.imid');
        if (config('nicepay-config.dev') == true) {
            $imid = config('nicepay-config.dev_imid');
        }

        return $imid;
    }

    /**
     * return string
     */
    protected function getMerchantKey()
    {
        $merchantKey = config('nicepay-config.merchant_key');
        if (config('nicepay-config.dev') == true) {
            $merchantKey = config('nicepay-config.dev_merchant_key');
        }

        return $merchantKey;
    }

    /**
     * return string
     */
    protected function getBaseUrl()
    {
        $baseUrl = config('nicepay-config.base_url');
        if (config('nicepay-config.dev') == true) {
            $baseUrl = config('nicepay-config.dev_base_url');
        }

        return $baseUrl;
    }

}