<?php

return [
    'salt' => env('HASHIDS_SALT') ?: env('APP_KEY'),
    'largo' => 8,
];
