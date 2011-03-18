<?php
return array(
  'app/HomeScreen.php' => array(
    '<?php',
    'class HomeScreen {',
    '  public function render() {',
    "    echo 'Welcome!';",
    '  }',
    '}',
  ),
  'app/error/internal_server_error/InternalServerErrorScreen.php' => array(
    '<?php',
    'class InternalServerErrorScreen {',
    '  public function render() {',
    "    echo '500 Internal Server Error';",
    '  }',
    '}',
  ),
  'app/error/not_found/NotFoundScreen.php' => array(
    '<?php',
    'class NotFoundScreen {',
    '  public function render() {',
    "    echo '404 Not Found';",
    '  }',
    '}',
  ),
  'cache/',
  'config/error_handler.config.php' => array(
    '<?php',
    'return array(',
    "  '404 Not Found' => '/error/not_found',",
    "  '500 Internal Server Error' => '/error/internal_server_error',",
    ');',
  ),
  'config/make.config.php' => array(
    '<?php',
    'return array(',
    "  'Application' => array('Action', 'View'),",
    "  'ClassLoader' => array(",
    "    'app', 'lib', HF_PATH.'web'.DIRECTORY_SEPARATOR.'lib',",
    "  ),",
    ');',
  ),
  'lib/',
  'public/index.php' => array(
    '<?php',
    "define('ROOT_PATH', dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR);",
    "define('CACHE_PATH', ROOT_PATH.'cache'.DIRECTORY_SEPARATOR);",
    "define('CONFIG_PATH', ROOT_PATH.'config'.DIRECTORY_SEPARATOR);",
    "define('DATA_PATH', ROOT_PATH.'data'.DIRECTORY_SEPARATOR);",
    "define('HF_PATH', ".HF_PATH.");",
    "require(",
    "  HF_PATH.'class_loader'.DIRECTORY_SEPARATOR.",
    "  'lib'.DIRECTORY_SEPARATOR.'ClassLoader.php'",
    ");",
    '$classLoader = new ClassLoader;',
    '$classLoader->run();',
    '$app = new Application',
    '$errorHandler = new ErrorHandler($app);',
    '$errorHandler->run();',
    '$app->run();',
  ),
  'test/',
  'vendor/',
);