<?php

define('XTAIN_FILTER_QUERY_BUILDER_PATH', __DIR__);
define('XTAIN_FILTER_QUERY_BUILDER_SERIALIZER_CONFIG_PATH', XTAIN_FILTER_QUERY_BUILDER_PATH . DIRECTORY_SEPARATOR . 'serializer');

call_user_func(function() {
    $composer = json_decode(file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'composer.json'), true);
    define('XTAIN_FILTER_QUERY_BUILDER_SERIALIZER_NAMESPACE_PREFIX', rtrim(key($composer['autoload']['psr-4']), '\\'));
});