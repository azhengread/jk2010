<?php
return array(
  'app/WelcomeCommand.php' => array(
    '<?php',
    'class WelcomeCommand {',
    '  public function execute() {',
    "    echo 'Welcome!'.PHP_EOL;",
    '  }',
    '}',
  ),
  'cache/' => 0777,
  'config/build.config.php' => array(
    '<?php',
    'return array(',
    "  'ClassLoader' => array('app', 'lib', HYPERFRAMEWORK_PATH.'cli/lib'),",
    ');',
   ),
  'config/command_application.config.php' => array(
    '<?php',
    'return array(',
    "  'description' => 'Add your own description here',",
    "  'sub' => array(",
    "    'welcome' => 'WelcomeCommand',",
    '  ),',
    ');',
  ),
  'lib/',
  'public/index.php' => array(
    0755,
    '#!/usr/bin/env php',
    '<?php',
    "define('ROOT_PATH', dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR);",
    "define('CACHE_PATH', ROOT_PATH.'cache'.DIRECTORY_SEPARATOR);",
    "define('CONFIG_PATH', ROOT_PATH.'config'.DIRECTORY_SEPARATOR);",
    "define('HYPERFRAMEWORK_PATH', ".$GLOBALS['HYPERFRAMEWORK_PATH'].');',
    'require '.$GLOBALS['CLASS_LOADER_PREFIX']
      .".'class_loader'.DIRECTORY_SEPARATOR",
    "  .'lib'.DIRECTORY_SEPARATOR.'ClassLoader.php';",
    '$classLoader = new ClassLoader;',
    '$classLoader->run();',
    '$exceptionHandler = new CommandExceptionHandler;',
    '$exceptionHandler->run();',
    '$app = new CommandApplication;',
    '$app->run();',
   ),
  'test/bootstrap.php' => array(
    '<?php',
    "define('TEST_PATH', dirname(__FILE__).DIRECTORY_SEPARATOR);",
    "define('ROOT_PATH', TEST_PATH.'fixture'.DIRECTORY_SEPARATOR);",
    "define('CACHE_PATH', ROOT_PATH.'cache'.DIRECTORY_SEPARATOR);",
    "define('CONFIG_PATH', ROOT_PATH.'config'.DIRECTORY_SEPARATOR);",
    "define('HYPERFRAMEWORK_PATH', ".$GLOBALS['HYPERFRAMEWORK_PATH'].');',
    'require '.$GLOBALS['CLASS_LOADER_PREFIX']
      .".'class_loader'.DIRECTORY_SEPARATOR",
    "  .'lib'.DIRECTORY_SEPARATOR.'ClassLoader.php';",
    '$classLoader = new ClassLoader;',
    '$classLoader->run();',
  ),
  'test/case/app/WelcomeCommandTest.php' => array(
    "<?php",
    "class WelcomeCommandTest extends PHPUnit_Framework_TestCase {",
    "  public function test() {",
    "  }",
    "}"
  ),
  'test/fixture/cache/' => 0777,
  'test/fixture/config/build.config.php' => array(
    '<?php',
    "return array('ClassLoader' => array('lib'));",
   ),
  'test/fixture/lib/',
  'vendor/',
);