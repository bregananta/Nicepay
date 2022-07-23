<?php

return [
    'base_url' => env('NICEPAY_BASE_URL', ''),
    'imid' => env('NICEPAY_IMID', ''),
    'merchant_key' => env('NICEPAY_MERCHANT_KEY', ''),
    'callback_url' => env('NICEPAY_CALLBACK_URL', ''),
    'dbprocess_url' => env('NICEPAY_DBPROCESS_URL', ''),
    'log' => env('NICEPAY_LOG', true),
    'dev' => env('NICEPAY_DEV', true),
    'dev_base_url' => env('NICEPAY_BASE_URL_DEV', 'https://dev.nicepay.co.id/'),
    'dev_imid' => env('NICEPAY_IMID_DEV', 'IONPAYTEST'),
    'dev_merchant_key' => env('NICEPAY_MERCHANT_KEY_DEV', '33F49GnCMS1mFYlGXisbUDzVf2ATWCl9k3R++d5hDd3Frmuos/XLx8XhXpe+LDYAbpGKZYSwtlyyLOtS/8aD7A=='),
    'dev_callback_url' => env('NICEPAY_CALLBACK_URL_DEV', ''),
    'dev_dbprocess_url' => env('NICEPAY_DBPROCESS_URL_DEV', 'http://ptsv2.com/t/0ftrz-1519971382/post')
];