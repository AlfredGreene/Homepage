<?php

$app->match('/screensaver/', function() use($app) {
    /** @var \Service\FeedService $feedService */
    $feedService = $app['feedService'];
    $feedItems = $feedService->getFeedItems(50, (new DateTime())->setTime(-6, 0));
    $weatherService = $app['weatherService'];

    return $app['twig']->render('screensaver/index.html.twig', [
        'forecast' => $weatherService->getForecastList(),
        'feedItems'=> $feedItems,
        'firstFeedItem' => $feedItems[0],
        'secondFeedItem' => $feedItems[1],
        'lastUpdate' => [
            'css_main' => filemtime(__DIR__ . '/../assets/css/style.css'),
            'css_mobile' => filemtime(__DIR__ . '/../assets/css/mobile.css'),
            'css_screensaver' => filemtime(__DIR__ . '/../assets/css/screensaver.css'),
            'js_main' => filemtime(__DIR__ . '/../assets/scripts/main.js'),
            'js_mobile' => filemtime(__DIR__ . '/../assets/scripts/mobile.js'),
            'js_screensaver' => filemtime(__DIR__ . '/../assets/scripts/screensaver.js'),
        ],
    ]);
});