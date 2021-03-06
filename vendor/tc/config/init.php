<?php
namespace Tc;

return [
//    '[hyperframework]',
    'hyperframework.app_root_namespace' => __NAMESPACE__,
    'hyperframework.cli.command_collection.enable' => true,
    'hyperframework.asset.concatenate_manifest' => false,
    'hyperframework.asset.enable_versioning' => true,
    'hyperframework.asset.enable_proxy' => true,
//    'hyperframework.log_handler.log_path' => APP_ROOT_PATH . '/../log/app.log',
    'hyperframework.log.enable_proxy' => true,
//    'hyperframework.autoload_files.enable' => true,
//    'hyperframework.path_info.enable_cache' => false,
//    'hyperframework.class_loader.root_path' => 'phar://' . ROOT_PATH . '/tmp/cache/lib.phar',
    'hyperframework.use_composer_autoloader' => true,
//    'hyperframework.path_info.enable_cache' => false,
    'hyperframework.web.error_handler.exit_level' => 'WARNING',
//    'hyperframework.class_loader.enable_zero_folder' => true,
    'hyperframework.db.profiler.enable' => true,
    'hyperframework.logger.level' => 'DEBUG',
    'hyperframework.error_handler.enable_logger' => true,
//    'hyperframework.log_handler.path' => 'php://output',
];
