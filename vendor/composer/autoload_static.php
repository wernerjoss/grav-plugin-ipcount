<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit51a20d96330b67f697e7c91435252dfd
{
    public static $prefixLengthsPsr4 = array (
        'J' => 
        array (
            'Jaybizzle\\CrawlerDetect\\' => 24,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Jaybizzle\\CrawlerDetect\\' => 
        array (
            0 => __DIR__ . '/..' . '/jaybizzle/crawler-detect/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit51a20d96330b67f697e7c91435252dfd::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit51a20d96330b67f697e7c91435252dfd::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}