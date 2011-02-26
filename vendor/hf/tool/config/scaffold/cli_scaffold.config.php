<?php
return array(
  'app/HelpCommand.php' => array(
    '<?php',
    'class HelpCommand {',
    '  public function execute($target = \'main\') {',
    '    echo \'help!\';',
    '  }',
    '}',
  ),
  'bin/bin.bat' => array(
    '@echo off',
    'php "hf.php" %*',
  ),
  'bin/bin.php' => array(
    '#!/usr/bin/php',
    '<?php',
    "ini_set('display_errors', 0);",
    "define('ROOT_PATH', dirname(dirname(__FILE__)).'/');",
    "define('HF_PATH', dirname(dirname(ROOT_PATH)).'/');",
    "define('HF_CACHE_PATH', ROOT_PATH.'cache/hf/');",
    "define('HF_CONFIG_PATH', ROOT_PATH.'config/hf/');",
    "require HF_PATH.'class_loader/lib/ClassLoader.php';",
    '$classLoader = new ClassLoader;',
    '$classLoader->run();',
    '$errorHandler = new CommandErrorHandler;',
    '$errorHandler->run();',
    '$parser = new CommandParser;',
    '$parser->parse();',
   ),
  'bin/bin.sh' => array(
    '@echo off',
    'php "hf.php" %*',
  ),
  'cache',
  'config/hf/make.config.php',
  'config/hf/cli/CommandParser.config.php' => array(
    '<?php',
    'return array(',
    "  'option' => array(",
    "    'version' => array(",
    "      'short' => 'v',",
    "      'expansion' => array('help', 'version'),",
    "      'description' => 'print version infomation',",
    '    ),',
    "    'help' => array(",
    "      'short' => array('h', '?'),",
    "      'expansion' => array('help'),",
    "      'description' => 'show help',",
    "    ),",
    "  ),",
    "  'sub' => array(",
    "    'help' => 'HelpCommand',",
    "  ),",
    "  'default_sub' => array('help'),",
    ');',
  ),
  'lib',
  'test',
  'vendor',
);