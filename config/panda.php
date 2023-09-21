<?php

// This setting file is specific to our Panda app, hence the name panda.php
// This should return values of env variables from cache (this file is cached automatically in production)

return [
    'cdn' => env('CDN_URL', env('AWS_URL')),
];