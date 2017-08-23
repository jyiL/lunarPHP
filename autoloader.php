<?php

namespace LunarPHP;

/**
 *
 * 自动载入函数
 * User: ljy
 * Date: 17-08-18
 */
class Autoloader
{
    const NAMESPACE_PREFIX = 'LunarPHP\\';

    public static function register()
    {
        spl_autoload_register(array(new self, 'autoload'));
    }

    /**
     * 根据类名载入所在文件
     */
    public static function autoload($className)
    {
        $namespacePrefixStrlen = strlen(self::NAMESPACE_PREFIX);
        if ( strncmp(self::NAMESPACE_PREFIX, $className, $namespacePrefixStrlen) === 0 ){
            $className = strtolower($className);
            $filePath = str_replace('\\', DIRECTORY_SEPARATOR, substr($className, $namespacePrefixStrlen));
            $filePath = realpath(__DIR__ . ( empty($filePath) ? '' : DIRECTORY_SEPARATOR) . $filePath . '.lrp.php' );
            if ( file_exists($filePath) ) {
                require_once $filePath;
            } else {
                echo $filePath;
            }
        }
    }
}