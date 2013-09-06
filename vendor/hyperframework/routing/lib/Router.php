<?php
namespace Hyperframework\Routing;

class Router {
    public static function execute($path = null) {
        $result = static::parse($path);
        $redirectType = HierarchyChecker::check($result['path']);
        $path = $result['path'];
        if ($redirectType !== null) {
            $path = static::adjustPath($path, $redirectType);
        }
        static::initializeLink($path, $result['parameters'], $redirectType);
        if ($redirectType !== null) {
            $tmp = explode('?', $_SERVER['REQUEST_URI'], 2);
            $path = $tmp[0] === $result['path'] ?
                $path : $path = static::adjustPath($tmp[0], $redirectType);
            $queryString = '';
            if (isset($tmp[1])) {
                $queryString = '?' . $tmp[1];
            }
            static::redirect(static::getLocation($path, $queryString));
            return;
        }
        return $path;
    }

    protected static function parse($path = null) {
        return PathInExtensionParser::parse($path);
    }

    protected static function initializeLink(
        $path, $parameters, $redirectType
    ) {
        $pathInfo = \Hyperframework\Web\PathInfo::get($path);
        if (isset($pathInfo['Link']['initialization'])) {
            $pathInfo['Link']['class']::initialize($parameters);
        }
    }

    protected static function getLocation($path, $queryString){
        return static::getProtocol($path) . '://' .
            static::getDomain($path) . $path . $queryString;
    }

    protected static function getProtocol() {
        if (isset($_SERVER['HTTPS'])) {
            return 'https';
        }
        return 'http';
    }

    protected static function getDomain() {
        return $_SERVER['HTTP_HOST'];
    }

    protected static function redirect($location) {
        if ($_SERVER['REQUEST_METHOD'] === 'GET' || 
            $_SERVER['REQUEST_METHOD'] === 'HEAD') {
            header('HTTP/1.1 301 Moved Permanently');
            header('Location: ' . $location);
        }
    }

    private static function adjustPath($path, $redirectType) {
        if ($redirectType === HierarchyChecker::FILE) {
            return substr($path, 0, strlen($path) - 1);
        }
        return $path . '/';
    }
}
