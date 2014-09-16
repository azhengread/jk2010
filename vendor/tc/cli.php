#!/usr/bin/env php
<?php
namespace Tc;

define('Tc\ROOT_PATH', __DIR__);
require ROOT_PATH . DIRECTORY_SEPARATOR . 'config'
    . DIRECTORY_SEPARATOR . 'init_const.php';
require HYPERFRAMEWORK_PATH . DIRECTORY_SEPARATOR . 'Cli'
    . DIRECTORY_SEPARATOR . 'Runner.php';
\Hyperframework\Cli\Runner::run(__NAMESPACE__, ROOT_PATH);
