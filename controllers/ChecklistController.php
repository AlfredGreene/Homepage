<?php
/**
 * Created by PhpStorm.
 * User: petervandam
 * Date: 31/01/2017
 * Time: 19:58
 */

$app->match('/checklist/', function() use($app) {

    $checklistService = $app['checklistService'];
    $todos = $checklistService->getTodos();
    $finished = $checklistService->getFinished();

    return $app['twig']->render('checklist/index.html.twig', [
        'lastUpdate' => [
            'css_main' => filemtime(__DIR__ . '/../assets/css/style.css'),
            'css_mobile' => filemtime(__DIR__ . '/../assets/css/mobile.css'),
            'js_main' => filemtime(__DIR__ . '/../assets/scripts/main.js'),
            'js_mobile' => filemtime(__DIR__ . '/../assets/scripts/mobile.js'),
        ],
        'todos' => $todos,
        'finished' => $finished
    ]);
});

$app->match('/checklist/add/', function() use($app) {


    /** @var \Service\ChecklistService $checklistService */
    $checklistService = $app['checklistService'];
    $checklistService->saveChecklistItem(
        isset($_POST['id']) && !empty($_POST['id']) ? $_POST['id'] : null,
        isset($_POST['item']) && !empty($_POST['item']) ? $_POST['item'] : '',
        isset($_POST['checked']) && !empty($_POST['checked']) ? $_POST['checked'] : false
    );

    return 'Done';
});