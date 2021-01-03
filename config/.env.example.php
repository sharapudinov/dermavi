<?php

use System\CacheEngines\CacheEngineLocalFiles;

return [
    'APP_ENV' => 'dev',
    'APP_DEBUG' => true,

    'CACHE_TYPE' => class_exists(CacheEngineLocalFiles::class) ? ['class_name' => CacheEngineLocalFiles::class] : 'files',
    'CACHE_SID' => $_SERVER["DOCUMENT_ROOT"]."#01"
];
