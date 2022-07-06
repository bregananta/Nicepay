<?php

return [
    'base_url' => env('NICEPAY_BASE_URL', 'https://dev.nicepay.co.id/'),
    'imid' => env('NICEPAY_IMID', 'IONPAYTEST'),
    'merchant_key' => env('NICEPAY_MERCHANT_KEY', '33F49GnCMS1mFYlGXisbUDzVf2ATWCl9k3R++d5hDd3Frmuos/XLx8XhXpe+LDYAbpGKZYSwtlyyLOtS/8aD7A=='),
    'callback_url' => env('NICEPAY_CALLBACK_URL', ''),
    'dbprocess_url' => env('NICEPAY_DBPROCESS_URL', 'http://ptsv2.com/t/0ftrz-1519971382/post'),
    'log' => env('NICEPAY_LOG', true)
];