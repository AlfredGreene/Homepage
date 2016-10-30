<?php

require_once __DIR__ . '/../vendor/autoload.php';

$container = new \Pimple\Container();

require_once __DIR__ . '/../app/config.php';
require_once __DIR__ . '/../app/services.php';

$feedService = new \Service\FeedService($container['']);
foreach ($container['feed.adapters'] as $key => $feedAdapter) {
    $feedService->registerAdapter($key, $feedAdapter);
}

$feedService->import();