<?php
namespace Hyperframework\Cli;

use ReflectionMethod;
use Exception;

class ArgumentConfigParser {
    public static function _test() {}

    public static function parse($config) {
        if (is_array($config) === false) {
            $config = array($config);
        }
        $result = [];
        foreach ($config as $item) {
            $result[] = static::parseItem($item);
        }
        return $result;
    }

    private static function parseItem($config) {
        $isOptional = false;
        $isCollection = false;
        $length = strlen($config);
        if ($length < 3) {
            throw new Exception;
        }
        if ($config[0] === '[') {
            $isOptional = true;
            if ($config[$length - 1] !== ']') {
                throw new Exception;
            }
            $config = substr($config, 1, $length - 2);
            $length -= 2;
            if ($length < 3) {
                throw new Exception;
            }
        }
        if ($config[0] === '<') {
            if (substr($config, -3) === '...') {
                $config = substr($config, 0, $length - 3);
                $length -= 3;
                $isCollection = true;
                if ($length < 3) {
                    throw new Exception;
                }
            }
            if ($config[$length - 1] !== '>') {
                throw new Exception;
            }
            $name = substr($config, 1, $length - 2);
            if (preg_match('/^[a-zA-Z0-9-]+$/', $name) !== 1) {
                throw new Exception;
            } else {
                return array(
                    'name' => $name,
                    'is_optional' => $isOptional,
                    'is_collection' => $isCollection
                );
            }
        } else {
            throw new Exception;
        }
    }
}
