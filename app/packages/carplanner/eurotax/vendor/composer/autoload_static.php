<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitc6257ec9be474c2d4d0181b917252080
{
    public static $prefixLengthsPsr4 = array (
        'C' => 
        array (
            'CarPlanner\\Eurotax\\Tests\\' => 25,
            'CarPlanner\\Eurotax\\' => 19,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'CarPlanner\\Eurotax\\Tests\\' => 
        array (
            0 => __DIR__ . '/../..' . '/tests',
        ),
        'CarPlanner\\Eurotax\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
            1 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'CarPlanner\\Eurotax\\EurotaxService' => __DIR__ . '/../..' . '/src/EurotaxService.php',
        'CarPlanner\\Eurotax\\EurotaxServiceProvider' => __DIR__ . '/../..' . '/src/EurotaxServiceProvider.php',
        'CarPlanner\\Eurotax\\Interfaces\\EurotaxRequestInterface' => __DIR__ . '/../..' . '/src/Interfaces/EurotaxRequestInterface.php',
        'CarPlanner\\Eurotax\\Interfaces\\EurotaxServiceInterface' => __DIR__ . '/../..' . '/src/Interfaces/EurotaxServiceInterface.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitc6257ec9be474c2d4d0181b917252080::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitc6257ec9be474c2d4d0181b917252080::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitc6257ec9be474c2d4d0181b917252080::$classMap;

        }, null, ClassLoader::class);
    }
}
