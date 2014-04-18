<?php
namespace Hyperframework\Web;

use Hyperframework\Config;
use Hyperframework\ClassLoader;

class Runner {
    public static function run($applicationNamespace, $applicationPath) {
        define('Hyperframework\APPLICATION_NAMESPACE', $applicationNamespace);
        define('Hyperframework\APPLICATION_PATH', $applicationPath);
        static::initialize();
        $path = static::getPath();
        if (static::isAsset($path)) {
            static::runAssetProxy($path);
            return;
        }
        static::runApplication($path);
    }

    protected static function initialize() {
        static::initializeConfig();
        static::initializeClassLoader();
        static::initializeExceptionHandler();
    }

    protected static function getPath() {
        $segments = explode('?', $_SERVER['REQUEST_URI'], 2);
        $result = $segments[0];
        if ($result === '') {
            return '/';
        }
        if ($result[0] === '#') {
            throw new NotFoundException;
        }
        return $result;
    }

    protected static function isAsset($path) {
        return strncmp($path, '/asset/', 7) === 0;
    }

    protected static function runAssetProxy() {
        AssetProxy::run($path);
    }

    protected static function runApplication($path) {
        Application::run($path);
    }

    protected static function initializeConfig() {
        static::loadConfigClass();
        static::loadConfigs();
    }

    protected static function initializeClassLoader() {
        require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'ClassLoader.php';
        ClassLoader::run();
    }

    protected static function initializeExceptionHandler() {
        ExceptionHandler::run();
    }

    protected static function loadConfigClass() {
        require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'Config.php';
    }

    protected static function loadConfigs() {
        $configs = require \Hyperframework\APPLICATION_PATH
            . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'init.php';
        if ($configs !== null) {
            Config::import($configs);
        }
    }
}
