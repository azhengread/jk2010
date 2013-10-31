<?php
namespace Hyperframework;

class ConfigLoader {
    public static function load($pathConfigName, $defaultPath) {
        return DataLoader::load(
            'config', $pathConfigName, $defaultPath, 'config'
        );
    }
}
