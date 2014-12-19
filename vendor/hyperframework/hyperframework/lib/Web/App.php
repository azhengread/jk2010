<?php
namespace Hyperframework\Web;

use Exception;
use Hyperframework\Common\Config;
use Hyperframework\Common\NamespaceCombiner;

class App {
    private $router;

    public function __construct() {
        $this->rewriteRequestMethod();
        $this->checkCsrf();
    }

    public function run() {
        $controller = $this->createController();
        $controller->run();
        $this->finalize();
    }

    public function getRouter() {
        if ($this->router === null) {
            $class = Config::getString(
                'hyperframework.web.router_class', ''
            );
            if ($class === '') {
                $namespace = Config::get(
                    'hyperframework.app_root_namespace', ''
                );
                $class = 'Router';
                if ($namespace !== '' && $namespace !== '\\') {
                    NamespaceCombiner::prepend($class, $namespace);
                }
                if (class_exists($class) === false) {
                    throw new Exception($class . ' not found');
                }
            }
            $this->router = new $class($this);
        }
        return $this->router;
    }

    public function redirect($url, $statusCode = 302) {
        header('Location: ' . $url, true, $statusCode);
        $this->quit();
    }

    public function quit() {
        $this->finalize();
        exit;
    }

    protected function rewriteRequestMethod() {
        if (Config::getBoolean(
            'hyperframework.web.router.rewrite_request_method', true
        )) {
            $method = null;
            if (isset($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'])) {
                $method = $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'];
            } elseif (isset($_POST['_method'])) {
                $method = $_POST['_method'];
            }
            if ($method !== null && $method !== '') {
                $_SERVER['ORIGINAL_REQUEST_METHOD'] =
                    $_SERVER['REQUEST_METHOD'];
                $_SERVER['REQUEST_METHOD'] = strtoupper($method);
            }
        }
    }

    protected function checkCsrf() {
        CsrfProtection::run();
    }

    protected function createController() {
        $router = $this->getRouter();
        $class = (string)$router->getControllerClass();
        if ($class === '' || class_exists($class) === false) {
            throw new Exception;
        }
        return new $class($this);
    }

    protected function finalize() {}
}
