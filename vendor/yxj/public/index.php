<?php
namespace Yxj;

function initialize() {
    $rootPath = dirname(__DIR__) . DIRECTORY_SEPARATOR;
    require $rootPath . 'config' . DIRECTORY_SEPARATOR . 'env.config.php';
    Hyperframework\Config::setRootPath($rootPath);
    require HYPERFRAMEWORK_PATH . 'ClassLoader.php';
    Hyperframework\ClassLoader::run();
    Hyperframework\ExceptionHandler::run();
} initialize();

function run() {
    $path = Router::execute();
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['_method'])) {
        $_SERVER['REQUEST_METHOD'] = $_POST['_method'];
    }
    //TODO: 测试是否存在 session_id 的 cookie，如果存在，打开 session
    if ($path !== null) {
        Hyperframework\Application::run($path);
    }
} run();

//function finalize() {
//} finalize();
