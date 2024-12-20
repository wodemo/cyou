<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitc2b9a6bc3a27107230cf296da92fe44d
{
    public static $prefixLengthsPsr4 = array (
        'Y' => 
        array (
            'YooKassa\\Validator\\' => 19,
            'YooKassa\\' => 9,
        ),
        'P' => 
        array (
            'Psr\\Log\\' => 8,
        ),
        'D' => 
        array (
            'Ds\\' => 3,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'YooKassa\\Validator\\' => 
        array (
            0 => __DIR__ . '/..' . '/yoomoney/yookassa-sdk-validator/src',
        ),
        'YooKassa\\' => 
        array (
            0 => __DIR__ . '/..' . '/yoomoney/yookassa-sdk-php/lib',
        ),
        'Psr\\Log\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/log/src',
        ),
        'Ds\\' => 
        array (
            0 => __DIR__ . '/..' . '/php-ds/php-ds/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitc2b9a6bc3a27107230cf296da92fe44d::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitc2b9a6bc3a27107230cf296da92fe44d::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitc2b9a6bc3a27107230cf296da92fe44d::$classMap;

        }, null, ClassLoader::class);
    }
}
