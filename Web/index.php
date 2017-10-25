<?php
define('PM_APPLICATION_ROOT', dirname(__DIR__));

require_once(PM_APPLICATION_ROOT . '/Libraries/autoload.php');

$application = new \SebastiaanDeJonge\Pm\Application();
$application->run();