<?php

use Entity\FeedItem;

$app->post('/feed/add-item/', function() use ($app) {

    return json_encode(['status' => 'fail', 'message' => 'Function not available in Demo mode']);
});

$app->post('/feed/pin/{id}', function($id) use($app) {
    /** @var \Service\FeedService $feedService */
    $feedService = $app['feedService'];
    $feedService->pinItem($id);

    return json_encode(['status' => 'success', 'message' => 'Pin toggled']);
});

$app->get('/feed/refresh/{date}', function($date) use ($app) {
    /** @var \Service\FeedService $feedService */
    $feedService = $app['feedService'];

    return json_encode([
        'html' => $app['twig']->render('home/newsfeed.html.twig', [
            'feedItems'=> $feedService->getFeedItems(999, new \DateTime($date)),
            'feeds' => $feedService->getFeeds(),
            'nextPageNumber' => 50000,
            'addToChecklist' => '',
        ]),
        'refreshDate' => (new \DateTime())->format('Y-m-d H:i:s'),
    ]);
});

$app->get('/feed/page/{startIndex}', function($startIndex) use ($app) {
    /** @var \Service\FeedService $feedService */
    $feedService = $app['feedService'];
    return $app['twig']->render('home/newsfeed.html.twig', [
        'feedItems'=> $feedService->getFeedItems(50, null, $startIndex),
        'feeds' => $feedService->getFeeds(),
        'nextPageNumber' => $startIndex + 1,
        'addToChecklist' => '',
    ]);
});

$app->get('/feed/search/{startIndex}', function($startIndex) use ($app) {
    $query = isset($_GET['query']) ? $_GET['query'] : null;
    if (!$query) {
        return json_encode(['status' => 'fail', 'message' => 'Missing parameter(s): query']);
    }

    /** @var \Service\FeedService $feedService */
    $feedService = $app['feedService'];
    $feedItems = $feedService->getFeedItems(10, null, $startIndex, $query);

    return json_encode([
        'status' => 'success',
        'data' => array_map(function(FeedItem $feedItem) {
            return [
                'id' => $feedItem->getId(),
                'title' => $feedItem->getTitle(),
                'description' => $feedItem->getShortDescription(),
                'url' => $feedItem->getUrl(),
                'color' => $feedItem->getColor(),
                'feedIcon' => $feedItem->getFeedIcon(),
                'shareId' => $feedItem->getFeedName() . '/' . $feedItem->getId() . '/',
                'pinned' => $feedItem->isPinned()
            ];
        }, $feedItems)
    ]);
});

$app->get('/feeds/overview/', function() use($app) {

    /** @var \Service\FeedService $feedService */
    $feedService = $app['feedService'];

    return $app['twig']->render('widgets/feed-overview.html.twig', [
        'feeds' => $feedService->getFeedOverview()
    ]);

});

$app->post('/feeds/settingsupdate/', function() use($app) {
    return json_encode(['status' => 'fail', 'message' => 'Not available in Demo mode']);
});
