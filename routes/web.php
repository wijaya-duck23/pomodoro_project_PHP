<?php
/**
 * Application Routes
 * Define all routes for the application here
 */

// Timer routes
$router->get('timer', 'TimerController@index');
$router->get('', 'TimerController@index');  // Default route
$router->post('timer/save', 'TimerController@save');

// History routes
$router->get('history', 'HistoryController@index');
$router->get('history/list', 'HistoryController@list');
$router->get('history/filter', 'HistoryController@filter'); 