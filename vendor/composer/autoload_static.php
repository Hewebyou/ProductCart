<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit5764f5e9827e5a137e793317cc713187
{
    public static $files = array (
        '4aa2080e11e3e009fd082b521aae5573' => __DIR__ . '/../..' . '/src/helpers.php',
    );

    public static $prefixLengthsPsr4 = array (
        'H' => 
        array (
            'Heesapp\\Productcart\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Heesapp\\Productcart\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit5764f5e9827e5a137e793317cc713187::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit5764f5e9827e5a137e793317cc713187::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
