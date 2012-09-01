<?php
require_once dirname(__FILE__) . '/../vendor/SplClassLoader.php';
$loader = new SplClassLoader('Pinoy', dirname(__FILE__) . '/../src');
$loader->setNamespaceSeparator('_');
$loader->register();
