<?php
/**
 * Services are globally registered in this file
 *
 * @var \Phalcon\Config $config
 */

use Phalcon\CLI\Console as ConsoleApp;
use Phalcon\DI\FactoryDefault\CLI as CliDI;
use Phalcon\Mvc\Collection\Manager;

// Using the CLI factory default services container
$di = new CliDI();

$di->set('mongo', function () {
    $mongo = new MongoClient();
    return $mongo->selectDB("braincore2");
}, true);

//Register a collection manager
$di->set('collectionManager', function() {
    return new Manager();

});

include "modules/predis/autoload.php";
Predis\Autoloader::register();



$client = new Predis\Client();

$di->set('redis',$client);

if (!defined('APP_PATH')) {
    define('APP_PATH', dirname(__DIR__));
}

/**
 * Register the autoloader and tell it to register the tasks directory
 */
$loader = new \Phalcon\Loader();
$loader->registerNamespaces(array(
    'Modules\Frontend\Controllers' => __DIR__ . '/controllers/',
    'Modules\BusinessLogic\Models' => 'modules/BusinessLogic/models/',
    'Modules\BusinessLogic\Frontend' => 'modules/BusinessLogic/Frontend/',
    'Modules\BusinessLogic\ContentSettings' => 'modules/BusinessLogic/ContentSettings/',
    'Modules\BusinessLogic\Search' => 'modules/BusinessLogic/Searches/',
));
$loader->registerDirs(
    array(
        APP_PATH . '/tasks',
    )
);

$loader->register();


// Create a console application
$console = new ConsoleApp();
$console->setDI($di);

/**
 * Process the console arguments
 */
$arguments = array();
foreach ($argv as $k => $arg) {
    if ($k == 1) {
        $arguments['task'] = $arg;
    } elseif ($k == 2) {
        $arguments['action'] = $arg;
    } elseif ($k >= 3) {
        preg_match('/^--(.*)=(.*)/ism', $arg, $mathes);
        if (!empty($mathes[1])) {
            $arguments['params'][$mathes[1]] = $mathes[2];
        } else {
            $arguments['params'][] = $arg;
        }
    }
}

if (!empty($arguments['params'][0])) {
    echo 'Invalid parameter format: "' . $arguments['params'][0] . '"' . PHP_EOL;
    echo 'Valid format example: --env=test' . PHP_EOL;
    die();
}

// Define global constants for the current task and action
define('CURRENT_TASK', (isset($argv[1]) ? $argv[1] : null));
define('CURRENT_ACTION', (isset($argv[2]) ? $argv[2] : null));

try {
    // Handle incoming arguments
    $console->handle($arguments);
} catch (\Exception $e) {
    echo $e->getMessage() . PHP_EOL;
    exit($e->getCode());
}