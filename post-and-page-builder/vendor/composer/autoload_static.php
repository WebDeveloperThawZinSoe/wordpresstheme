<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit76cb6026cf544b9231c46672ddc240c4
{
    public static $prefixLengthsPsr4 = array (
        'B' => 
        array (
            'Boldgrid\\Library\\Util\\' => 22,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Boldgrid\\Library\\Util\\' => 
        array (
            0 => __DIR__ . '/..' . '/boldgrid/library/src/Util',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit76cb6026cf544b9231c46672ddc240c4::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit76cb6026cf544b9231c46672ddc240c4::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}