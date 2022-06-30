<?php

namespace Bregananta\Nicepay\Tests\Feature;

use Bregananta\Nicepay\Facades\Nicepay;
use Bregananta\Nicepay\Tests\TestCase;
use Carbon\Carbon;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

class NicepayTest extends TestCase
{
    /** @test */
    public function register_virtual_account_transaction_test()
    {
        Http::preventStrayRequests();

        Http::fake();

        Nicepay::registerVa('CENA', '20220619', '203100', 10000, 'ORD12345',
        'Test Transaction Nicepay', 'Customer Name', '12345678',
        'email@merchant.com');

        $timeStamp = Carbon::parse(now('Asia/Jakarta'));
        $timeStamp = $timeStamp->format('YmdHis');

        $iMid = config('nicepay-config.imid');
        $merchantKey = config('nicepay-config.merchant_key');

        $merchantToken = hash('sha256', $timeStamp . $iMid . 'ORD12345' . '10000' . $merchantKey);

        Http::assertSent(function (Request $request) use ($timeStamp, $merchantToken) {
            return $request['timeStamp'] == $timeStamp &&
            $request['iMid'] == config('nicepay-config.imid') &&
            $request['payMethod'] == '02' &&
            $request['currency'] == 'IDR' &&
            $request['amt'] == '10000' &&
            $request['referenceNo'] == 'ORD12345' &&
            $request['goodsNm'] == 'Test Transaction Nicepay' &&
            $request['billingNm'] == 'Customer Name' &&
            $request['billingPhone'] == '12345678' &&
            $request['billingEmail'] == 'email@merchant.com' &&
            $request['dbProcessUrl'] == 'http://ptsv2.com/t/0ftrz-1519971382/post' &&
            $request['merchantToken'] == $merchantToken &&
            $request['bankCd'] == 'CENA' &&
            $request['vacctValidDt'] == '20220619' &&
            $request['vacctValidTm'] == '203100';
        });
    }
}