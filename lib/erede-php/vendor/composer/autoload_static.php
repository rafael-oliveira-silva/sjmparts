<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit65a98634911263e6892e24eee1c7dd90
{
    public static $prefixLengthsPsr4 = array (
        'R' => 
        array (
            'Rede\\' => 5,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Rede\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/Rede',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit65a98634911263e6892e24eee1c7dd90::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit65a98634911263e6892e24eee1c7dd90::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
