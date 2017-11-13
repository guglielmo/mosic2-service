<?php

use Doctrine\Common\Annotations\AnnotationRegistry;
use Composer\Autoload\ClassLoader;

/**
 * @var ClassLoader $loader
 */
$loader = require __DIR__.'/../vendor/autoload.php';
$loader->add('DoctrineExtensions', __DIR__.'/../vendor/beberlei/lib/DoctrineExtensions');

AnnotationRegistry::registerLoader([$loader, 'loadClass']);

return $loader;
