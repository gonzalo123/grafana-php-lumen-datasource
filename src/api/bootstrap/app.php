<?php

use Laravel\Lumen\Routing\Router;
use App\Http\Middleware;
use Laravel\Lumen\Application;
use Dotenv\Dotenv;
use App\Http\Handlers;

require_once __DIR__ . '/../vendor/autoload.php';

(Dotenv::create(__DIR__ . '/../env/local'))->load();

$app = new Application(dirname(__DIR__));
$app->middleware([
    Middleware\CorsMiddleware::class,
]);

$app->router->group(['middleware' => Middleware\AuthMiddleware::class], function (Router $router) {
    $router->get('/', Handlers\HelloHandler::class);
    $router->post('/search', Handlers\SearchHandler::class);
    $router->post('/query', Handlers\QueryHandler::class);
    $router->post('/annotations', Handlers\AnnotationHandler::class);
});

return $app;
