<?php

namespace ASB\Status\App;

class AsbClassMap
{
    public static function getClassMap()
    {
        if (!file_exists(__DIR__ . '/../config/classMap.json')) {
            return [];
        }
        $classMapJson = file_get_contents(__DIR__ . '/../config/classMap.json');
        return json_decode($classMapJson, true) ?? [];
    }

    public static function putClassMap(array $classes): false|int
    {
        $classMap = json_encode($classes);
        return file_put_contents(__DIR__ . '/../config/classMap.json', $classMap);
    }

    public static function getClassName(string $class)
    {
        return strtolower(substr($class, strrpos($class, '\\', -1) + 1));
    }

    public static function handler($class): void
    {
        if (!file_exists(__DIR__ . '/../config/classMap.json')) {
            static::putClassMap([static::getClassName($class) => $class]);
            return;
        }
        $classes = AsbClassMap::getClassMap();
        in_array($class, $classes) ?: $classes[static::getClassName($class)] = $class;
        static::putClassMap($classes);
    }
}
